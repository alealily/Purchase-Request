<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\PurchaseRequest;
use App\Services\ApprovalWorkflowService;

$service = app(ApprovalWorkflowService::class);

// Get PRs
$prs = PurchaseRequest::with(['user', 'prDetails'])->take(3)->get();

echo "=== Testing Approval Chain Logic ===\n\n";

foreach ($prs as $pr) {
    $user = $pr->user;
    $totalCost = $pr->prDetails->total_cost ?? 0;
    
    echo "PR #{$pr->pr_number}\n";
    echo "  User: {$user->name} (Division: {$user->division})\n";
    echo "  Total Cost: Rp " . number_format($totalCost, 0, ',', '.') . "\n";
    echo "  High Cost (>25M): " . ($totalCost > 25000000 ? 'Yes' : 'No') . "\n";
    
    $chain = $service->determineApprovalChain($pr);
    echo "  Approval Chain:\n";
    foreach ($chain as $level) {
        $approver = $service->getApproverForLevel($level);
        $approverName = $approver ? $approver->name : 'NOT FOUND';
        echo "    Level {$level['level']}: {$level['position']} -> {$approverName}\n";
    }
    echo "\n";
}

// Check Dika
$dika = User::where('name', 'Dika')->first();
echo "=== Dika Info ===\n";
echo "Role: {$dika->role}\n";
echo "Position: {$dika->position}\n";
echo "Division: {$dika->division}\n";
