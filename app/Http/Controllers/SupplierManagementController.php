<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierManagementController extends Controller
{
    /**
     * Display a listing of suppliers with search and pagination.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $query = Supplier::query();
        
        // Search by name, email, phone, or address
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
            });
        }
        
        $suppliers = $query->orderBy('id_supplier', 'desc')->paginate(4);
        
        // Preserve search in pagination links
        $suppliers->appends(['search' => $search]);
        
        return view('supplier.index', compact('suppliers', 'search'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return view('supplier.create');
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string',
        ]);

        // Apply Title Case to name
        Supplier::create([
            'name' => ucwords(strtolower($validated['name'])),
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        return redirect()
            ->route('supplier_management.index')
            ->with('success', 'Supplier added successfully');
    }

    /**
     * Display the specified supplier.
     */
    public function show(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('supplier.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string',
        ]);

        // Apply Title Case to name
        $supplier->update([
            'name' => ucwords(strtolower($validated['name'])),
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        return redirect()
            ->route('supplier_management.index')
            ->with('success', 'Supplier updated successfully');
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        // Check if supplier is used in any PR
        if ($supplier->purchaseRequest()->exists()) {
            return redirect()
                ->route('supplier_management.index')
                ->with('error', 'Cannot delete supplier that is used in Purchase Requests');
        }
        
        $supplier->delete();

        return redirect()
            ->route('supplier_management.index')
            ->with('success', 'Supplier deleted successfully');
    }

    /**
     * Export suppliers to Excel
     */
    public function export()
    {
        date_default_timezone_set('Asia/Jakarta');
        $filename = 'SupplierManagement_' . date('dmY_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SupplierExport, $filename);
    }
}
