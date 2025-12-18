<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class SupplierExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithMapping
{
    /**
     * Mengambil data supplier dari database
     */
    public function collection()
    {
        return Supplier::orderBy('id_supplier', 'desc')->get();
    }

    /**
     * Mapping data untuk setiap row di Excel
     */
    public function map($supplier): array
    {
        return [
            $supplier->name ?? '-',
            $supplier->address ?? '-',
            $supplier->phone ?? '-',
            $supplier->email ?? '-',
        ];
    }

    /**
     * Mendefinisikan header kolom Excel
     */
    public function headings(): array
    {
        return [
            'NAME',
            'ADDRESS',
            'TELEPHONE',
            'EMAIL',
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
