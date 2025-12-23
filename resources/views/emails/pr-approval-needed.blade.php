<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approval Needed</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #F59E0B;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0;
            font-size: 24px;
        }
        .pending-badge {
            display: inline-block;
            background-color: #F59E0B;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            margin-top: 10px;
        }
        .content h2 {
            color: #1F2937;
            font-size: 18px;
        }
        .pr-details {
            background-color: #F9FAFB;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .pr-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .pr-details td {
            padding: 8px 0;
            border-bottom: 1px solid #E5E7EB;
        }
        .pr-details td:first-child {
            font-weight: 600;
            color: #6B7280;
            width: 40%;
        }
        .pr-details tr:last-child td {
            border-bottom: none;
        }
        .requester-info {
            background-color: #EEF2FF;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .btn-action {
            display: inline-block;
            background-color: #4F46E5;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 20px;
        }
        .btn-action:hover {
            background-color: #4338CA;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            color: #6B7280;
            font-size: 12px;
        }
        .urgent-note {
            background-color: #FEE2E2;
            border-left: 4px solid #EF4444;
            padding: 12px;
            margin-top: 20px;
            border-radius: 0 6px 6px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>PT Infinity</h1>
            <span class="pending-badge">⏳ Approval Needed</span>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $approver->name }}</strong>,</p>
            
            <p>A new Purchase Request requires your approval. Please review the details below:</p>
            
            <div class="requester-info">
                <strong>Requested by:</strong> {{ $requester->name }}<br>
                <strong>Department:</strong> {{ $requester->department ?? '-' }}<br>
                <strong>Division:</strong> {{ $requester->division ?? '-' }}
            </div>
            
            <h2>Purchase Request Details</h2>
            <div class="pr-details">
                <table>
                    <tr>
                        <td>PR Number</td>
                        <td><strong>#{{ $pr->pr_number }}</strong></td>
                    </tr>
                    <tr>
                        <td>Material Description</td>
                        <td>{{ $prDetails->material_desc ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Quantity</td>
                        <td>{{ $prDetails->quantity ?? 0 }} {{ $prDetails->uom ?? 'PCS' }}</td>
                    </tr>
                    <tr>
                        <td>Unit Price</td>
                        <td>Rp {{ number_format($prDetails->unit_price ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Total Cost</td>
                        <td><strong>Rp {{ number_format($prDetails->total_cost ?? 0, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
            
            <div class="urgent-note">
                <strong>⚡ Action Required</strong><br>
                Please log in to the system to review and approve/reject this Purchase Request.
            </div>
            
            <center>
                <a href="{{ url('/purchase_request/' . $pr->id_pr) }}" class="btn-action">
                    View Purchase Request
                </a>
            </center>
        </div>
        
        <div class="footer">
            <p>This is an automated email from PT Infinity Purchase Request System.<br>
            Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
