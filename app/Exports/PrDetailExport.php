<?php

namespace App\Exports;

use App\Models\PurchaseRequest;
use App\Models\Approval;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class PrDetailExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithMapping
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Mengambil data purchase request berdasarkan role user
     */
    public function collection()
    {
        $userRole = strtolower($this->user->role ?? '');
        
        $prQuery = PurchaseRequest::with(['prDetails.supplier', 'user']);
        
        if ($userRole === 'it') {
            // IT: Can see ALL PRs
            // No filter needed
        } elseif (in_array($userRole, ['head of department', 'head of division', 'general manager', 'president director'])) {
            // Approvers: Show PRs where:
            // 1. User has already actioned (approve/reject/revision) - for tracking
            // 2. OR User is the current pending approver (their turn)
            
            // Get PRs user has already actioned
            $actionedPrIds = Approval::where('id_user', $this->user->id_user)
                                     ->whereIn('approval_status', ['approve', 'approved', 'reject', 'rejected', 'revision'])
                                     ->pluck('id_pr')
                                     ->toArray();
            
            // Get PRs where user is current pending approver
            $pendingApprovals = Approval::where('id_user', $this->user->id_user)
                                        ->where('approval_status', 'pending')
                                        ->get();
            
            $currentTurnPrIds = [];
            foreach ($pendingApprovals as $approval) {
                $minPendingLevel = (int) Approval::where('id_pr', $approval->id_pr)
                                                  ->where('approval_status', 'pending')
                                                  ->min('level');
                if ((int) $approval->level === $minPendingLevel) {
                    $currentTurnPrIds[] = $approval->id_pr;
                }
            }
            
            // Combine both sets
            $prIds = array_unique(array_merge($actionedPrIds, $currentTurnPrIds));
            
            if (empty($prIds)) {
                return collect([]); // Return empty collection
            } else {
                $prQuery->whereIn('id_pr', $prIds);
            }
        } else {
            // Employee: Can see their own PRs only
            $prQuery->where('id_user', $this->user->id_user);
        }
        
        return $prQuery->orderBy('created_at', 'desc')->get();
    }

    /**
     * Mapping data untuk setiap row di Excel
     */
    public function map($purchaseRequest): array
    {
        $prDetail = $purchaseRequest->prDetails;
        
        return [
            $purchaseRequest->pr_number ?? '-',
            ucfirst($purchaseRequest->status ?? '-'),
            $prDetail->material_desc ?? '-',
            $prDetail->uom ?? 'PCS',
            $prDetail->unit_price ?? 0,
            $prDetail->currency_code ?? 'RP',
            $prDetail->quantity ?? 0,
            $prDetail->total_cost ?? 0,
            $purchaseRequest->created_at ? $purchaseRequest->created_at->format('d-m-Y H:i') : '-',
            $prDetail->supplier->name ?? '-',
            $purchaseRequest->user->name ?? '-',
            $purchaseRequest->user->department ?? '-',
            $purchaseRequest->user->division ?? '-',
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
            'USER',
            'DEPARTMENT',
            'DIVISION',
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
