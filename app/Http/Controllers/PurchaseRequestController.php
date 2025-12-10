<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseRequest;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        {
            $pr = PurchaseRequest::all();
            return view('purchase_request.index', compact('pr'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Export purchase requests to Excel
     */
    public function export()
    {
        // Set timezone to Jakarta for correct local time
        date_default_timezone_set('Asia/Jakarta');
        
        // Format: SubmitPurchaseRequest_ddMMyyyy_HHmmss.xlsx
        $filename = 'SubmitPurchaseRequest_' . date('dmY_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PurchaseRequestExport, $filename);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
