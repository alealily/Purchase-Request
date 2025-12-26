<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revision Requested</title>
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
        .revision-badge {
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
        .reviewer-info {
            background-color: #FEF3C7;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .remarks-box {
            background-color: #FFFBEB;
            border: 1px solid #FDE68A;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .remarks-box strong {
            color: #D97706;
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
        .action-note {
            background-color: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 12px;
            margin-top: 20px;
            border-radius: 0 6px 6px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>PT SIIX ELECTRONICS INDONESIA</h1>
            <span class="revision-badge">Revision Requested</span>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $requester->name }}</strong>,</p>
            
            <p>Your Purchase Request requires revision. Please review the feedback below and make the necessary changes.</p>
            
            <div class="reviewer-info">
                <strong>Requested by:</strong> {{ $reviewer->name }}<br>
                <strong>Position:</strong> {{ ucwords(str_replace('_', ' ', $reviewer->position ?? '-')) }}<br>
                <strong>Date:</strong> {{ now()->format('d M Y, H:i') }}
            </div>
            
            <h2>Revision Feedback</h2>
            <div class="remarks-box">
                <strong>Remarks:</strong><br>
                {{ $remarks }}
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
                        <td>Total Cost</td>
                        <td><strong>Rp {{ number_format($prDetails->total_cost ?? 0, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
            
            <div class="action-note">
                <strong>⚡ Action Required</strong><br>
                Please log in to the system to edit and resubmit your Purchase Request with the requested changes.
            </div>
            
            <center>
                <a href="{{ url('/purchase-request/' . $pr->id_pr . '/edit') }}" class="btn-action">
                    Edit Purchase Request
                </a>
            </center>
        </div>
        
        <div class="footer">
            <p>This is an automated email from PT SIIX ELECTRONICS INDONESIA.<br>
            Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
