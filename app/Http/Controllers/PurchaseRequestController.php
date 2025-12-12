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
     * Display a listing of the resource.
     */
    public function index()
    {
        $pr = PurchaseRequest::with(['prDetails.supplier', 'user'])
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        return view('purchase_request.index', compact('pr'));
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
            // Generate PR Number
            $lastPR = PurchaseRequest::latest('id_pr')->first();
            $nextNumber = $lastPR ? intval(substr($lastPR->pr_number, -6)) + 1 : 1;
            $prNumber = '10' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

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
                'material_desc' => $validated['material_desc'],
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
        
        $approvalHistory = $this->approvalService->getApprovalHistory($pr);
        $canApprove = $this->approvalService->canUserApprove(auth()->user(), $pr);
        
        return view('purchase_request.show', compact('pr', 'approvalHistory', 'canApprove'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pr = PurchaseRequest::with('prDetails')->findOrFail($id);
        $suppliers = Supplier::all();
        
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
            'quotation_file' => 'nullable|file|mimes:pdf,docx|max:10240',
        ]);

        try {
            $pr = PurchaseRequest::findOrFail($id);
            
            // Update PR Detail
            $totalCost = $validated['unit_price'] * $validated['quantity'];
            
            $prDetail = $pr->prDetails;
            $prDetail->material_desc = $validated['material_desc'];
            $prDetail->unit_price = $validated['unit_price'];
            $prDetail->quantity = $validated['quantity'];
            $prDetail->total_cost = $totalCost;
            $prDetail->id_supplier = $validated['id_supplier'];
            
            // Handle file upload
            if ($request->hasFile('quotation_file')) {
                $file = $request->file('quotation_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('quotations', $filename, 'public');
                $prDetail->quotation_file = $filename;
            }
            
            $prDetail->save();
            
            // Reset approval if status was revision
            if ($pr->status === 'revision') {
                $pr->update(['status' => 'pending']);
                // Recreate approval chain
                $pr->approvals()->delete();
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
