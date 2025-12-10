<?php

namespace App\Exports;

use App\Models\PurchaseRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class PurchaseRequestExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithMapping
{
    /**
     * Mengambil data purchase request dari database
     */
    public function collection()
    {
        // Eager load prDetails and supplier (supplier is in pr_detail table)
        return PurchaseRequest::with(['prDetails.supplier'])->get();
    }

    /**
     * Mapping data untuk setiap row di Excel
     */
    public function map($purchaseRequest): array
    {
        $prDetail = $purchaseRequest->prDetails;
        
        return [
            $purchaseRequest->pr_number ?? '-',
            $purchaseRequest->status ?? '-',
            $prDetail->material_desc ?? '-',
            $prDetail->uom ?? 'PCS',
            $prDetail->unit_price ?? 0,
            $prDetail->currency_code ?? 'RP',
            $prDetail->quantity ?? 0,
            $prDetail->total_cost ?? 0,
            $purchaseRequest->created_at ? $purchaseRequest->created_at->format('d-m-Y') : '-',
            $prDetail->supplier->name ?? '-',
        ];
    }

    /**
     * Mendefinisikan header kolom Excel
     */
    public function headings(): array
    {
        return [
            'PR NUMBER',
            'STATUS',
            'MATERIAL DESC',
            'UOM',
            'UNIT PRICE',
            'CURRENCY',
            'QUANTITY',
            'TOTAL COST',
            'CREATED AT',
            'SUPPLIER',
        ];
    }

    /**
     * Apply styling ke worksheet Excel
     */
    public function styles(Worksheet $sheet)
    {
        // Freeze header row (row pertama)
        $sheet->freezePane('A2');

        // Style untuk header row
        return [
            // Header row (row 1)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFD9D9D9'], // Abu-abu (gray)
                ],
            ],
        ];
    }
}
