<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use App\Models\PrDetail;
use App\Models\Supplier;
use App\Services\ApprovalWorkflowService;

class PurchaseRequestController extends Controller
{
    protected $approvalService;

    public function __construct(ApprovalWorkflowService $approvalService)
    {
        $this->approvalService = $approvalService;
    }

    /**
     * Display a listing of the resource with search and filters.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        // Filter parameters
        $filters = [
            'status' => $request->query('status'),
            'material' => $request->query('material'),
            'supplier' => $request->query('supplier'),
            'quantity' => $request->query('quantity'),
            'unit_price' => $request->query('unit_price'),
            'total_cost' => $request->query('total_cost'),
            'date_from' => $request->query('date_from'),
            'date_to' => $request->query('date_to'),
        ];
        
        // Only show PRs created by the logged-in user with pagination (4 per page)
        $query = PurchaseRequest::with(['prDetails.supplier', 'user'])
                            ->where('id_user', auth()->id());
        
        // Search by PR number or material description
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('pr_number', 'like', '%' . $search . '%')
                  ->orWhereHas('prDetails', function($prDetail) use ($search) {
                      $prDetail->where('material_desc', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('prDetails.supplier', function($supplier) use ($search) {
                      $supplier->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Apply server-side filters
        if ($filters['status']) {
            $query->where('status', 'like', '%' . $filters['status'] . '%');
        }
        if ($filters['material']) {
            $query->whereHas('prDetails', function($q) use ($filters) {
                $q->where('material_desc', 'like', '%' . $filters['material'] . '%');
            });
        }
        if ($filters['supplier']) {
            $query->whereHas('prDetails.supplier', function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['supplier'] . '%');
            });
        }
        if ($filters['quantity']) {
            $query->whereHas('prDetails', function($q) use ($filters) {
                $q->where('quantity', '>=', (int) $filters['quantity']);
            });
        }
        if ($filters['unit_price']) {
            $query->whereHas('prDetails', function($q) use ($filters) {
                $q->where('unit_price', '>=', (int) $filters['unit_price']);
            });
        }
        if ($filters['total_cost']) {
            $query->whereHas('prDetails', function($q) use ($filters) {
                $q->where('total_cost', '>=', (int) $filters['total_cost']);
            });
        }
        if ($filters['date_from']) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if ($filters['date_to']) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        
        $pr = $query->orderBy('created_at', 'desc')->paginate(4);
        
        // Preserve search and filters in pagination links
        $pr->appends(array_merge(['search' => $search], $filters));
        
        return view('purchase_request.index', compact('pr', 'search', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('purchase_request.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'material_desc' => 'required|string',
            'unit_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'id_supplier' => 'required|exists:supplier,id_supplier',
            'quotation_files' => 'required|array|max:3', // Max 3 files
            'quotation_files.*' => 'file|mimes:pdf,doc,docx|max:10240', // 10MB max each
        ], [
            'quotation_files.required' => 'Please upload at least one quotation file.',
            'quotation_files.max' => 'You can upload maximum 3 files.',
            'quotation_files.*.mimes' => 'Only PDF and Word documents are allowed.',
            'quotation_files.*.max' => 'Each file must not exceed 10MB.',
        ]);

        try {
            // Generate PR Number (format: 1000020400, 1000020401, 1000020402, ...)
            $baseNumber = 1000020400;
            $lastPR = PurchaseRequest::orderBy('id_pr', 'desc')->first();
            
            if ($lastPR && is_numeric($lastPR->pr_number) && intval($lastPR->pr_number) >= $baseNumber) {
                // If last PR number is already in new format, increment it
                $prNumber = strval(intval($lastPR->pr_number) + 1);
            } else {
                // First PR or migration from old format - start with base number
                $prNumber = strval($baseNumber);
            }

            // Create Purchase Request
            $pr = PurchaseRequest::create([
                'pr_number' => $prNumber,
                'id_user' => auth()->id(),
                'status' => 'pending',
            ]);

            // Handle multiple file uploads
            $uploadedFiles = [];
            if ($request->hasFile('quotation_files')) {
                foreach ($request->file('quotation_files') as $file) {
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $file->storeAs('quotations', $filename, 'public');
                    $uploadedFiles[] = $filename;
                }
            }
            
            // Store as JSON if multiple files, or single filename
            $quotationFile = count($uploadedFiles) > 0 ? json_encode($uploadedFiles) : null;

            // Create PR Detail
            $totalCost = $validated['unit_price'] * $validated['quantity'];
            
            PrDetail::create([
                'id_pr' => $pr->id_pr,
                'id_user' => auth()->id(),
                'id_supplier' => $validated['id_supplier'],
                'material_desc' => ucwords(strtolower($validated['material_desc'])),
                'uom' => 'PCS', // Default
                'unit_price' => $validated['unit_price'],
                'currency_code' => 'RP', // Default
                'quantity' => $validated['quantity'],
                'total_cost' => $totalCost,
                'quotation_file' => $quotationFile,
            ]);

            // Create approval chain
            $this->approvalService->createApprovalChain($pr);

            return redirect()
                ->route('purchase_request.index')
                ->with('success', 'Purchase Request submitted successfully and sent for approval.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create Purchase Request: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pr = PurchaseRequest::with(['prDetails.supplier', 'user', 'approvals.user'])
                            ->findOrFail($id);
        
        $user = auth()->user();
        $userRole = strtolower($user->role ?? '');
        $superiorRoles = ['head of department', 'head of division', 'general manager', 'president director'];
        
        // Check access rights
        $canView = false;
        
        // Owner can always view their own PR
        if ($pr->id_user === $user->id_user) {
            $canView = true;
        }
        // IT can view all PRs
        elseif ($userRole === 'it') {
            $canView = true;
        }
        // Superiors can view if they are an approver for this PR
        elseif (in_array($userRole, $superiorRoles)) {
            $canView = $pr->approvals->contains('id_user', $user->id_user);
        }
        
        if (!$canView) {
            return redirect()
                ->route('purchase_request.index')
                ->with('error', 'You are not authorized to view this Purchase Request.');
        }
        
        $approvalHistory = $this->approvalService->getApprovalHistory($pr);
        $canApprove = $this->approvalService->canUserApprove($user, $pr);
        
        return view('purchase_request.show', compact('pr', 'approvalHistory', 'canApprove'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pr = PurchaseRequest::with('prDetails')->findOrFail($id);
        $suppliers = Supplier::all();
        
        // Check ownership - only allow user to edit their own PR
        if ($pr->id_user !== auth()->id()) {
            return redirect()
                ->route('purchase_request.index')
                ->with('error', 'You are not authorized to edit this Purchase Request.');
        }
        
        // Only allow edit if status is pending or revision
        if (!in_array($pr->status, ['pending', 'revision'])) {
            return redirect()
                ->route('purchase_request.index')
                ->with('error', 'Cannot edit Purchase Request with status: ' . $pr->status);
        }
        
        return view('purchase_request.edit', compact('pr', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'material_desc' => 'required|string',
            'unit_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'id_supplier' => 'required|exists:supplier,id_supplier',
            'quotation_files' => 'nullable|array|max:3', // Max 3 files
            'quotation_files.*' => 'file|mimes:pdf,doc,docx|max:10240', // 10MB max each
        ]);

        try {
            $pr = PurchaseRequest::findOrFail($id);
            
            // Check ownership - only allow user to update their own PR
            if ($pr->id_user !== auth()->id()) {
                return redirect()
                    ->route('purchase_request.index')
                    ->with('error', 'You are not authorized to update this Purchase Request.');
            }
            
            // Update PR Detail
            $totalCost = $validated['unit_price'] * $validated['quantity'];
            
            $prDetail = $pr->prDetails;
            $prDetail->material_desc = ucwords(strtolower($validated['material_desc']));
            $prDetail->unit_price = $validated['unit_price'];
            $prDetail->quantity = $validated['quantity'];
            $prDetail->total_cost = $totalCost;
            $prDetail->id_supplier = $validated['id_supplier'];
            
            // Handle multiple file uploads (only if new files were uploaded)
            if ($request->hasFile('quotation_files')) {
                $uploadedFiles = [];
                foreach ($request->file('quotation_files') as $file) {
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $file->storeAs('quotations', $filename, 'public');
                    $uploadedFiles[] = $filename;
                }
                // Store as JSON
                $prDetail->quotation_file = json_encode($uploadedFiles);
            }
            
            $prDetail->save();
            
            // Reset approval if status was revision
            if ($pr->status === 'revision') {
                $pr->update(['status' => 'pending']);
                
                // Delete only pending/cancelled approvals (preserve revision/approve/reject history)
                $pr->approvals()
                   ->whereIn('approval_status', ['pending', 'cancelled'])
                   ->delete();
                
                // Recreate approval chain for new round
                $this->approvalService->createApprovalChain($pr);
            }

            return redirect()
                ->route('purchase_request.index')
                ->with('success', 'Purchase Request updated successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update Purchase Request: ' . $e->getMessage());
        }
    }

    /**
     * Approve Purchase Request
     */
    public function approve(Request $request, string $id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        
        $success = $this->approvalService->approvePR(
            $pr,
            auth()->user(),
            $request->input('remarks')
        );

        if ($success) {
            return redirect()
                ->back()
                ->with('success', 'Purchase Request approved successfully.');
        }

        return redirect()
            ->back()
            ->with('error', 'You are not authorized to approve this Purchase Request.');
    }

    /**
     * Reject Purchase Request
     */
    public function reject(Request $request, string $id)
    {
        $request->validate([
            'remarks' => 'required|string',
        ]);

        $pr = PurchaseRequest::findOrFail($id);
        
        $success = $this->approvalService->rejectPR(
            $pr,
            auth()->user(),
            $request->input('remarks')
        );

        if ($success) {
            return redirect()
                ->back()
                ->with('success', 'Purchase Request rejected.');
        }

        return redirect()
            ->back()
            ->with('error', 'You are not authorized to reject this Purchase Request.');
    }

    /**
     * Request revision for Purchase Request
     */
    public function revision(Request $request, string $id)
    {
        $request->validate([
            'remarks' => 'required|string',
        ]);

        $pr = PurchaseRequest::findOrFail($id);
        
        $success = $this->approvalService->revisionPR(
            $pr,
            auth()->user(),
            $request->input('remarks')
        );

        if ($success) {
            return redirect()
                ->back()
                ->with('success', 'Revision requested. Creator will be notified.');
        }

        return redirect()
            ->back()
            ->with('error', 'You are not authorized to request revision for this Purchase Request.');
    }

    /**
     * Export purchase requests to Excel
     */
    public function export()
    {
        date_default_timezone_set('Asia/Jakarta');
        $filename = 'SubmitPurchaseRequest_' . date('dmY_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PurchaseRequestExport, $filename);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $pr = PurchaseRequest::findOrFail($id);
            
            // Check ownership - only allow user to delete their own PR
            if ($pr->id_user !== auth()->id()) {
                return redirect()
                    ->route('purchase_request.index')
                    ->with('error', 'You are not authorized to delete this Purchase Request.');
            }
            
            // Only allow delete if status is pending or revision
            if (!in_array($pr->status, ['pending', 'revision'])) {
                return redirect()
                    ->back()
                    ->with('error', 'Cannot delete Purchase Request with status: ' . $pr->status);
            }
            
            // Delete related records
            $pr->prDetails()->delete();
            $pr->approvals()->delete();
            $pr->delete();

            return redirect()
                ->route('purchase_request.index')
                ->with('success', 'Purchase Request deleted successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete Purchase Request: ' . $e->getMessage());
        }
    }
}
