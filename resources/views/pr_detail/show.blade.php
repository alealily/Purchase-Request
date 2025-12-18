@extends('layouts.app')

@section('title', 'Purchase Request Detail')

@section('content')
<div class="flex bg-[#F2F1F1] min-h-screen">
    {{-- Sidebar (dynamic based on role) --}}
    @php
        $userRole = strtolower(auth()->user()->role ?? '');
        $superiorRoles = ['head of department', 'head of division', 'general manager', 'president director'];
        $isSuperior = in_array($userRole, $superiorRoles);
    @endphp
    <aside class="w-64 flex-shrink-0">
        @if($userRole === 'it')
            @include('components.it_sidebar')
        @elseif($isSuperior)
            @include('components.superior_sidebar')
        @else
            @include('components.employee_sidebar')
        @endif
    </aside>

    <div class="flex-1 p-10">
        {{-- Header --}}
        <div class="bg-[#187FC4] text-white rounded-2xl mb-8 py-4 px-6 flex justify-between items-center">
            <h1 class="font-bold text-xl">Purchase Request #{{ $pr->pr_number }}</h1>
            @php
                $statusColors = [
                    'pending' => 'bg-[#FFEEB7] text-[#FF8110]',
                    'approved' => 'bg-[#B7FCC9] text-[#0A7D0C]',
                    'approve' => 'bg-[#B7FCC9] text-[#0A7D0C]',
                    'rejected' => 'bg-[#FFB3BA] text-[#E20030]',
                    'reject' => 'bg-[#FFB3BA] text-[#E20030]',
                    'revision' => 'bg-[#DFE0FF] text-[#0A0E8D]',
                ];
                $statusClass = $statusColors[strtolower($pr->status)] ?? 'bg-gray-200 text-gray-800';
            @endphp
            <span class="px-4 py-1 rounded-full text-sm font-semibold {{ $statusClass }}">
                {{ ucfirst($pr->status) }}
            </span>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-3 gap-6">
            {{-- Main Info Card --}}
            <div class="col-span-2 bg-white p-6 rounded-2xl shadow-sm">
                <h2 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">
                    Purchase Request Details
                </h2>
                
                <div class="grid grid-cols-2 gap-4">
                    {{-- PR Number --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">PR Number</label>
                        <p class="font-semibold text-gray-800">{{ $pr->pr_number }}</p>
                    </div>

                    {{-- Requester --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Requester</label>
                        <p class="font-semibold text-gray-800">{{ $pr->user->name ?? '-' }}</p>
                    </div>

                    {{-- Department --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Department</label>
                        <p class="font-semibold text-gray-800">{{ $pr->user->department ?? '-' }}</p>
                    </div>

                    {{-- Division --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Division</label>
                        <p class="font-semibold text-gray-800">{{ $pr->user->division ?? '-' }}</p>
                    </div>

                    {{-- Created Date --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Created At</label>
                        <p class="font-semibold text-gray-800">{{ $pr->created_at ? $pr->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') : '-' }}</p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Status</label>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                            {{ ucfirst($pr->status) }}
                        </span>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="border-t border-gray-200 my-6"></div>

                {{-- Material Details --}}
                <h3 class="text-md font-bold text-gray-800 mb-4">Material Information</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    {{-- Material Description --}}
                    <div class="col-span-2">
                        <label class="block text-sm text-gray-500 mb-1">Material Description</label>
                        <p class="font-semibold text-gray-800">{{ $pr->prDetails->material_desc ?? '-' }}</p>
                    </div>

                    {{-- UOM --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">UOM</label>
                        <p class="font-semibold text-gray-800">{{ $pr->prDetails->uom ?? 'PCS' }}</p>
                    </div>

                    {{-- Currency --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Currency</label>
                        <p class="font-semibold text-gray-800">RP</p>
                    </div>

                    {{-- Unit Price --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Unit Price</label>
                        <p class="font-semibold text-[#187FC4]">Rp{{ number_format($pr->prDetails->unit_price ?? 0, 0, ',', '.') }}</p>
                    </div>

                    {{-- Quantity --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Quantity</label>
                        <p class="font-semibold text-gray-800">{{ $pr->prDetails->quantity ?? 0 }}</p>
                    </div>

                    {{-- Total Cost --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Total Cost</label>
                        <p class="font-semibold text-[#187FC4] text-lg">Rp{{ number_format($pr->prDetails->total_cost ?? 0, 0, ',', '.') }}</p>
                    </div>

                    {{-- Supplier --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Supplier</label>
                        <p class="font-semibold text-gray-800">{{ $pr->prDetails->supplier->name ?? '-' }}</p>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="border-t border-gray-200 my-6"></div>

                {{-- Quotation Files --}}
                <h3 class="text-md font-bold text-gray-800 mb-4">Quotation Files</h3>
                @if($pr->prDetails && $pr->prDetails->quotation_file)
                    @php
                        $files = json_decode($pr->prDetails->quotation_file, true);
                        if (!is_array($files)) {
                            $files = [$pr->prDetails->quotation_file];
                        }
                    @endphp
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <ul class="space-y-3">
                            @foreach($files as $file)
                                @php
                                    $displayName = preg_replace('/^\d+_[a-f0-9]+_/', '', $file);
                                @endphp
                                <li class="grid grid-cols-[1fr_auto] gap-4 items-center">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <i class="fa-solid fa-file-pdf text-red-500 flex-shrink-0"></i>
                                        <a href="{{ url('/storage/quotations/' . $file) }}" 
                                           target="_blank" 
                                           class="text-blue-600 hover:underline font-medium truncate">
                                            {{ $displayName }}
                                        </a>
                                    </div>
                                    <a href="{{ url('/storage/quotations/' . $file) }}" 
                                       download 
                                       class="text-xs bg-gray-200 hover:bg-gray-300 px-3 py-1.5 rounded whitespace-nowrap flex items-center gap-1">
                                        <i class="fa-solid fa-download"></i> Download
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="text-gray-400 italic">No quotation files uploaded</p>
                @endif

                {{-- Back Button --}}
                <div class="flex justify-start items-center mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('pr_detail.index') }}" 
                       class="px-6 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-semibold transition">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
                    </a>
                </div>
            </div>

            {{-- Sidebar: Approval History & Actions --}}
            <div class="col-span-1 space-y-6">
                {{-- Approval Actions (for Current Approver) --}}
                @if($canApprove)
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">
                            Approval Action
                        </h2>
                        
                        <div class="space-y-3">
                            {{-- Approve Button --}}
                            <form action="{{ route('purchase_request.approve', $pr->id_pr) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="remarks" 
                                              placeholder="Remarks (optional)"
                                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                                              rows="2"></textarea>
                                </div>
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition">
                                    <i class="fa-solid fa-check mr-2"></i> Approve
                                </button>
                            </form>
                            
                            {{-- Revision Button --}}
                            <form action="{{ route('purchase_request.revision', $pr->id_pr) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="remarks" 
                                              placeholder="Reason for revision (required)"
                                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500"
                                              rows="2" required></textarea>
                                </div>
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition">
                                    <i class="fa-solid fa-rotate-left mr-2"></i> Revision
                                </button>
                            </form>
                            
                            {{-- Reject Button --}}
                            <form action="{{ route('purchase_request.reject', $pr->id_pr) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="remarks" 
                                              placeholder="Reason for rejection (required)"
                                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                                              rows="2" required></textarea>
                                </div>
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition">
                                    <i class="fa-solid fa-xmark mr-2"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Approval History Timeline --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">
                        Approval History
                    </h2>
                    
                    @if(count($approvalHistory) > 0)
                        <div class="relative">
                            @foreach($approvalHistory as $index => $history)
                                @php
                                    $status = strtolower($history['status'] ?? 'pending');
                                    $isLast = $loop->last;
                                    
                                    // Dot and line colors based on status
                                    $dotColors = [
                                        'approve' => 'bg-green-600',
                                        'approved' => 'bg-green-600',
                                        'reject' => 'bg-red-500',
                                        'rejected' => 'bg-red-500',
                                        'revision' => 'bg-purple-500',
                                        'pending' => 'bg-gray-300',
                                        'cancelled' => 'bg-gray-300',
                                    ];
                                    $dotColor = $dotColors[$status] ?? 'bg-gray-300';
                                    
                                    // Badge colors
                                    $badgeColors = [
                                        'approve' => 'bg-[#B7FCC9] text-[#0A7D0C]',
                                        'approved' => 'bg-[#B7FCC9] text-[#0A7D0C]',
                                        'reject' => 'bg-[#FFB3BA] text-[#E20030]',
                                        'rejected' => 'bg-[#FFB3BA] text-[#E20030]',
                                        'revision' => 'bg-[#DFE0FF] text-[#0A0E8D]',
                                        'pending' => 'bg-gray-200 text-gray-600',
                                        'cancelled' => 'bg-gray-200 text-gray-400',
                                    ];
                                    $badgeColor = $badgeColors[$status] ?? 'bg-gray-200 text-gray-600';
                                    
                                    // Status display text
                                    $statusDisplay = match($status) {
                                        'approve', 'approved' => 'Approved',
                                        'reject', 'rejected' => 'Rejected',
                                        'revision' => 'Revision',
                                        'pending' => 'Pending',
                                        'cancelled' => 'Cancelled',
                                        default => ucfirst($status),
                                    };
                                    
                                    // Line color (green if approved, gray otherwise)
                                    $lineColor = in_array($status, ['approve', 'approved']) ? 'bg-green-600' : 'bg-gray-200';
                                @endphp
                                
                                <div class="flex gap-4 {{ !$isLast ? 'pb-6' : '' }}">
                                    {{-- Timeline dot and line --}}
                                    <div class="relative flex flex-col items-center">
                                        {{-- Dot --}}
                                        <div class="w-4 h-4 rounded-full {{ $dotColor }} z-10"></div>
                                        {{-- Connecting line (not for last item) --}}
                                        @if(!$isLast)
                                            <div class="w-0.5 flex-1 {{ $lineColor }} mt-1"></div>
                                        @endif
                                    </div>
                                    
                                    {{-- Content --}}
                                    <div class="flex-1 -mt-1">
                                        {{-- Status Badge --}}
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $badgeColor }}">
                                            {{ $statusDisplay }}
                                        </span>
                                        
                                        {{-- Approver info --}}
                                        <p class="text-sm text-gray-700 mt-2">
                                            By <span class="font-bold">{{ $history['user'] ?? 'Unknown' }}</span>
                                            <span class="text-gray-500 text-xs">({{ $history['role'] ?? 'Unknown' }} - {{ $history['department'] ?? '-' }})</span>
                                            @if(!empty($history['date']))
                                                <br><span class="text-gray-400 text-xs">{{ $history['date'] }}</span>
                                            @endif
                                        </p>
                                        
                                        {{-- Remarks if any --}}
                                        @if(!empty($history['remarks']))
                                            <p class="text-xs text-gray-500 mt-1 italic bg-gray-50 p-2 rounded">
                                                "{{ $history['remarks'] }}"
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 text-sm italic">No approval history yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
