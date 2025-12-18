<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Purchase Request - {{ $pr->pr_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #187FC4;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #187FC4;
            font-size: 22px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 11px;
        }
        .pr-number {
            background: #187FC4;
            color: white;
            padding: 8px 15px;
            display: inline-block;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background: #f0f0f0;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 13px;
            border-left: 4px solid #187FC4;
            margin-bottom: 10px;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            padding: 5px 10px;
            width: 180px;
            font-weight: bold;
            background: #f9f9f9;
            border: 1px solid #ddd;
        }
        .info-value {
            display: table-cell;
            padding: 5px 10px;
            border: 1px solid #ddd;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .detail-table th {
            background: #187FC4;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
        }
        .detail-table td {
            padding: 10px 8px;
            border: 1px solid #ddd;
        }
        .detail-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-approve {
            background: #B7FCC9;
            color: #0A7D0C;
        }
        .approval-section {
            margin-top: 30px;
        }
        .approval-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        .approval-box {
            display: table-cell;
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .approval-box .role {
            font-weight: bold;
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }
        .approval-box .name {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .approval-box .signature {
            width: 80px;
            height: 50px;
            margin: 5px auto;
            object-fit: contain;
        }
        .approval-box .date {
            font-size: 10px;
            color: #666;
        }
        .approval-box .status {
            font-size: 10px;
            font-weight: bold;
            margin-top: 5px;
        }
        .status-approved { color: #0A7D0C; }
        .status-rejected { color: #E20030; }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PURCHASE REQUEST</h1>
        <p>PT. SIIX EMS Indonesia</p>
        <div class="pr-number">{{ $pr->pr_number }}</div>
    </div>

    <div class="section">
        <div class="section-title">Request Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Status</div>
                <div class="info-value">
                    <span class="status-badge status-approve">Approved</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Requester Name</div>
                <div class="info-value">{{ $pr->user->name ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Department</div>
                <div class="info-value">{{ $pr->user->department ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Division</div>
                <div class="info-value">{{ $pr->user->division ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Request Date</div>
                <div class="info-value">{{ $pr->created_at ? $pr->created_at->format('d F Y') : '-' }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Item Details</div>
        @php $detail = $pr->prDetails; @endphp
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Material Description</th>
                    <th>Supplier</th>
                    <th>UOM</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total Cost</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $detail->material_desc ?? '-' }}</td>
                    <td>{{ $detail->supplier->name ?? '-' }}</td>
                    <td>{{ $detail->uom ?? 'PCS' }}</td>
                    <td>{{ number_format($detail->quantity ?? 0, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($detail->unit_price ?? 0, 0, ',', '.') }}</td>
                    <td><strong>Rp{{ number_format($detail->total_cost ?? 0, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($detail->notes)
    <div class="section">
        <div class="section-title">Notes</div>
        <div style="padding: 10px; border: 1px solid #ddd; background: #f9f9f9;">
            {{ $detail->notes }}
        </div>
    </div>
    @endif

    <div class="section approval-section">
        <div class="section-title">Approval Signatures</div>
        <div class="approval-grid">
            @foreach($approvalHistory as $approval)
            <div class="approval-box">
                <div class="role">{{ strtoupper($approval['role']) }}</div>
                <div class="name">{{ $approval['name'] }}</div>
                @if(!empty($approval['signature']))
                    <img src="{{ public_path('storage/' . $approval['signature']) }}" class="signature" alt="signature">
                @else
                    <div style="height: 50px; border-bottom: 1px solid #333; width: 80px; margin: 10px auto;"></div>
                @endif
                <div class="date">
                    @if($approval['actioned_at'])
                        {{ \Carbon\Carbon::parse($approval['actioned_at'])->format('d M Y') }}
                    @endif
                </div>
                <div class="status {{ $approval['status'] === 'approve' ? 'status-approved' : ($approval['status'] === 'reject' ? 'status-rejected' : '') }}">
                    {{ strtoupper($approval['status']) }}
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="footer">
        <p>This document is computer generated and does not require a physical signature if digitally approved.</p>
        <p>Generated on: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
