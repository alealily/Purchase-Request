@extends('layouts.app')

@section('content')
    <div class="flex bg-[#F2F1F1] min-h-screen">
        {{-- Sidebar (dynamic based on role) --}}
        @php
            $userRole = strtolower(auth()->user()->role ?? '');
            $superiorRoles = ['head of department', 'head of division', 'president director', 'general manager'];
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

        {{-- Main content --}}
        <div class="flex-1 p-10 overflow-hidden">
            <div class="bg-[#187FC4] text-white rounded-2xl mb-[40px] flex items-center justify-between">
                <p class="ml-[25px] font-bold text-[25px]">Purchase Request Detail</p>
                <div class="relative p-5 mr-[5px]">
                    <i id="profileIconBtn"
                        class="fa-solid fa-user cursor-pointer text-xl hover:opacity-80 transition-opacity relative"></i>
                    @include('components.modal_profile')
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <form action="{{ route('pr_detail.index') }}" method="GET" class="flex items-center border border-gray-300 rounded-lg px-3 py-2 w-1/3">
                        <i class="fa-solid fa-search text-gray-400 mr-2"></i>
                        <input type="text" name="search" placeholder="Search purchase request" value="{{ $search ?? '' }}"
                            class="w-full focus:outline-none text-sm text-gray-600">
                    </form>

                    <div class="flex items-center gap-3">
                        <button id="filterBtn"
                            class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 text-sm font-medium">
                            <i class="fa-solid fa-filter"></i> Filter
                        </button>
                        <button id="exportBtn"
                            class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 text-sm font-medium">
                            <i class="fa-solid fa-file-export"></i> Export
                        </button>
                    </div>
                </div>

                <div class="max-w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-[1300px] w-full text-sm text-black">
                        <thead class="bg-gray-100 text-black">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-2 text-left">PR NUMBER</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">STATUS</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">MATERIAL DESC</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">UOM</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">UNIT PRICE</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">QUANTITY</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">TOTAL COST</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">CREATED AT</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">SUPPLIER</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">USER</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">DEPARTMENT</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">DIVISION</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">ACTION</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @forelse($purchaseRequests as $pr)
                                @php
                                    $detail = $pr->prDetails;
                                    $status = strtolower($pr->status ?? 'pending');
                                    
                                    // Status badge colors
                                    $statusColors = [
                                        'pending' => 'bg-[#FFEEB7] text-[#FF8110]',
                                        'approve' => 'bg-[#B7FCC9] text-[#0A7D0C]',
                                        'approved' => 'bg-[#B7FCC9] text-[#0A7D0C]',
                                        'reject' => 'bg-[#FFB3BA] text-[#E20030]',
                                        'rejected' => 'bg-[#FFB3BA] text-[#E20030]',
                                        'revision' => 'bg-[#DFE0FF] text-[#0A0E8D]',
                                    ];
                                    $badgeClass = $statusColors[$status] ?? 'bg-gray-200 text-gray-800';
                                @endphp
                                <tr class="hover:bg-gray-50 data-row">
                                    <td class="px-4 py-2">{{ $pr->pr_number }}</td>
                                    <td class="px-4 py-2">
                                        <span class="{{ $badgeClass }} px-3 py-1 rounded-full text-xs font-semibold">
                                            {{ ucfirst($pr->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">{{ $detail->material_desc ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $detail->uom ?? 'PCS' }}</td>
                                    <td class="px-4 py-2">Rp{{ number_format($detail->unit_price ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">{{ $detail->quantity ?? 0 }}</td>
                                    <td class="px-4 py-2">Rp{{ number_format($detail->total_cost ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">{{ $pr->created_at ? $pr->created_at->format('d M Y, H:i') : '-' }}</td>
                                    <td class="px-4 py-2">{{ $detail->supplier->name ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $pr->user->name ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $pr->user->department ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $pr->user->division ?? '-' }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- View Button --}}
                                            <a href="{{ route('pr_detail.show', $pr->id_pr) }}"
                                                class="bg-[#B6FDF4] text-[#15ADA5] p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]"
                                                title="View Detail">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="noResultsRow">
                                    <td colspan="13" class="px-4 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center gap-2">
                                            <i class="fa-solid fa-inbox text-4xl text-gray-300"></i>
                                            <p>No purchase requests found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination Links --}}
                @if($purchaseRequests->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $purchaseRequests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.data-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Show/hide no results message
            const noResultsRow = document.getElementById('noResultsRow');
            if (noResultsRow) {
                noResultsRow.style.display = visibleCount === 0 ? '' : 'none';
            }
        });
    </script>
@endsection