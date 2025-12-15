@extends('layouts.app')

@section('title', 'Purchase Request Detail')

@section('content')
<div class="flex bg-[#F4F5FA] min-h-screen">
    {{-- Sidebar (dynamic based on role) --}}
    @php
        $userRole = strtolower(auth()->user()->role ?? '');
        $superiorRoles = ['superior', 'head of department', 'head of division', 'president director'];
        $isSuperior = in_array($userRole, $superiorRoles);
    @endphp
    <aside class="w-64 bg-white h-screen sticky top-0">
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
                    'revised' => 'bg-[#D9D9D9] text-[#6E6D6D]',
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
                        <p class="font-semibold text-gray-800">{{ $pr->prDetails->currency_code ?? 'RP' }}</p>
                    </div>

                    {{-- Unit Price --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Unit Price</label>
                        <p class="font-semibold text-gray-800">{{ number_format($pr->prDetails->unit_price ?? 0, 0, ',', '.') }}</p>
                    </div>

                    {{-- Quantity --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Quantity</label>
                        <p class="font-semibold text-gray-800">{{ $pr->prDetails->quantity ?? 0 }}</p>
                    </div>

                    {{-- Total Cost --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Total Cost</label>
                        <p class="font-bold text-xl text-[#187FC4]">{{ number_format($pr->prDetails->total_cost ?? 0, 0, ',', '.') }}</p>
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
                        <ul class="space-y-2">
                            @foreach($files as $file)
                                @php
                                    $displayName = preg_replace('/^\d+_[a-f0-9]+_/', '', $file);
                                @endphp
                                <li class="flex items-center gap-3">
                                    <i class="fa-solid fa-file-pdf text-red-500"></i>
                                    <a href="{{ url('/storage/quotations/' . $file) }}" 
                                       target="_blank" 
                                       class="text-blue-600 hover:underline font-medium">
                                        {{ $displayName }}
                                    </a>
                                    <a href="{{ url('/storage/quotations/' . $file) }}" 
                                       download 
                                       class="text-xs bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded">
                                        <i class="fa-solid fa-download"></i> Download
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="text-gray-400 italic">No quotation files uploaded</p>
                @endif

                {{-- Action Buttons --}}
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('purchase_request.index') }}" 
                       class="px-6 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-semibold transition">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
                    </a>
                    
                    @if(in_array(strtolower($pr->status), ['pending', 'revision']))
                        <a href="{{ route('purchase_request.edit', $pr->id_pr) }}" 
                           class="px-6 py-2 bg-[#187FC4] text-white rounded-lg font-semibold hover:bg-[#156ca7] transition">
                            <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Request
                        </a>
                    @endif
                </div>
            </div>

            {{-- Sidebar: Approval History & Actions --}}
            <div class="col-span-1 space-y-6">
                {{-- Approval Actions (for Superiors) --}}
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
                                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                              rows="2"
                                              required></textarea>
                                </div>
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 transition">
                                    <i class="fa-solid fa-rotate-left mr-2"></i> Request Revision
                                </button>
                            </form>

                            {{-- Reject Button --}}
                            <form action="{{ route('purchase_request.reject', $pr->id_pr) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="remarks" 
                                              placeholder="Reason for rejection (required)"
                                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                                              rows="2"
                                              required></textarea>
                                </div>
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition">
                                    <i class="fa-solid fa-xmark mr-2"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Approval History --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">
                        Approval History
                    </h2>
                    
                    @if(count($approvalHistory) > 0)
                        <div class="space-y-4">
                            @foreach($approvalHistory as $history)
                                <div class="flex gap-3">
                                    {{-- Status Icon --}}
                                    @php
                                        $iconClass = match(strtolower($history['status'] ?? '')) {
                                            'approved' => 'fa-check text-green-500 bg-green-100',
                                            'rejected' => 'fa-xmark text-red-500 bg-red-100',
                                            'revision' => 'fa-rotate-left text-orange-500 bg-orange-100',
                                            'pending' => 'fa-clock text-yellow-500 bg-yellow-100',
                                            default => 'fa-circle text-gray-500 bg-gray-100',
                                        };
                                    @endphp
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full {{ $iconClass }} flex items-center justify-center">
                                            <i class="fa-solid {{ explode(' ', $iconClass)[0] }}"></i>
                                        </div>
                                    </div>
                                    
                                    {{-- Details --}}
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800 text-sm">{{ $history['user'] ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-500">{{ $history['role'] ?? '' }}</p>
                                        <p class="text-xs font-medium mt-1 
                                            {{ strtolower($history['status'] ?? '') == 'approved' ? 'text-green-600' : '' }}
                                            {{ strtolower($history['status'] ?? '') == 'rejected' ? 'text-red-600' : '' }}
                                            {{ strtolower($history['status'] ?? '') == 'revision' ? 'text-orange-600' : '' }}
                                            {{ strtolower($history['status'] ?? '') == 'pending' ? 'text-yellow-600' : '' }}
                                        ">
                                            {{ ucfirst($history['status'] ?? '-') }}
                                        </p>
                                        @if(!empty($history['remarks']))
                                            <p class="text-xs text-gray-600 mt-1 italic">"{{ $history['remarks'] }}"</p>
                                        @endif
                                        @if(!empty($history['date']))
                                            <p class="text-xs text-gray-400 mt-1">{{ $history['date'] }}</p>
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
