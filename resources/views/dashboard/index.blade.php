@extends('layouts.app')

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

        {{-- Main Content --}}
        <div class="flex-1 p-10 overflow-hidden">
            <!-- Header -->
            <div class="bg-[#187FC4] text-white rounded-2xl mb-[40px] flex items-center justify-between">
                <p class="ml-[25px] font-bold text-[25px]">Dashboard</p>
                <div class="relative p-5 mr-[5px]">
                    <i id="profileIconBtn"
                        class="fa-solid fa-user cursor-pointer text-xl hover:opacity-80 transition-opacity relative"></i>
                    @include('components.modal_profile')
                </div>
            </div>

            <!-- Cards with real data -->
            <div class="grid grid-cols-3 gap-20 mb-[40px]">
                <div class="bg-white p-6 rounded-2xl transition duration-400 ease-out hover:shadow-lg hover:scale-101">
                    <p class="text-[20px] text-[gray] font-semibold mb-[5px]">Purchase Request Pending</p>
                    <p class="text-3xl font-bold">{{ $pendingCount }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl transition duration-400 ease-out hover:shadow-lg hover:scale-101">
                    <p class="text-[20px] text-[gray] font-semibold mb-[5px]">Purchase Request Approve</p>
                    <p class="text-3xl font-bold">{{ $approvedCount }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl transition duration-400 ease-out hover:shadow-lg hover:scale-101">
                    <p class="text-[20px] text-[gray] font-semibold mb-[5px]">Purchase Request Reject</p>
                    <p class="text-3xl font-bold">{{ $rejectedCount }}</p>
                </div>
            </div>

            <!-- Table with real data -->
            <div class="bg-white p-6 rounded-2xl transition duration-400 ease-out hover:shadow-lg hover:scale-100">
                <!-- Header New Activity -->
                <p class="font-semibold text-[20px] border-b border-gray-300 pb-5 mb-4">New Activity</p>

                <!-- Table -->
                <div class="max-w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-[1300px] w-full text-sm text-black">
                        <thead class="bg-gray-100 text-black">
                            <tr class="text-left">
                                <th class="px-4 py-4">PR Number</th>
                                <th class="px-4 py-4">Status PR</th>
                                <th class="px-4 py-4">Material Description</th>
                                <th class="px-4 py-4">Quantity</th>
                                <th class="px-4 py-4">Unit Price</th>
                                <th class="px-4 py-4">Amount</th>
                                <th class="px-4 py-4">Datetime</th>
                                <th class="px-4 py-4">User</th>
                                <th class="px-4 py-4">Department</th>
                                <th class="px-4 py-4">Division</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPRs as $pr)
                                @php
                                    $detail = $pr->prDetails;
                                    $status = ucfirst($pr->status ?? 'pending');
                                    
                                    // Status badge colors
                                    $statusColors = [
                                        'Pending' => 'bg-[#FFEEB7] text-[#FF8110]',
                                        'Approve' => 'bg-[#B7FCC9] text-[#0A7D0C]',
                                        'Approved' => 'bg-[#B7FCC9] text-[#0A7D0C]',
                                        'Reject' => 'bg-[#FFB3BA] text-[#E20030]',
                                        'Rejected' => 'bg-[#FFB3BA] text-[#E20030]',
                                        'Revision' => 'bg-[#DFE0FF] text-[#0A0E8D]',
                                        'Revised' => 'bg-[#D9D9D9] text-[#6E6D6D]',
                                    ];
                                    $badgeClass = $statusColors[$status] ?? 'bg-gray-200 text-gray-800';
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4">{{ $pr->pr_number }}</td>
                                    <td class="px-4 py-4">
                                        <span class="{{ $badgeClass }} rounded-full px-3 py-1 text-[12px] font-bold">{{ $status }}</span>
                                    </td>
                                    <td class="px-4 py-4">{{ $detail->material_desc ?? '-' }}</td>
                                    <td class="px-4 py-4">{{ $detail->quantity ?? 0 }}</td>
                                    <td class="px-4 py-4">Rp{{ number_format($detail->unit_price ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-4">Rp{{ number_format($detail->total_cost ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-4">{{ $pr->created_at ? $pr->created_at->format('d M Y, H:i') : '-' }}</td>
                                    <td class="px-4 py-4">{{ $pr->user->name ?? '-' }}</td>
                                    <td class="px-4 py-4">{{ $pr->user->department ?? '-' }}</td>
                                    <td class="px-4 py-4">{{ $pr->user->division ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-4 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center gap-2">
                                            <i class="fa-solid fa-inbox text-4xl text-gray-300"></i>
                                            <p>No purchase requests yet.</p>
                                            @if(in_array($userRole, ['employee', 'it']))
                                                <a href="{{ route('purchase_request.create') }}" 
                                                   class="text-[#187FC4] hover:underline font-semibold">
                                                    Create your first purchase request
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @include('components.modal_profile')
        </div>
@endsection