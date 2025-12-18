@extends('layouts.app')

@section('content')
    <div class="flex bg-[#F2F1F1] min-h-screen">
        {{-- Sidebar (dynamic based on role) --}}
        @php
            $userRole = strtolower(auth()->user()->role ?? '');
            $superiorRoles = ['superior', 'head of department', 'head of division', 'president director'];
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

        <div class="flex-1 p-10 overflow-hidden">
            <div class="bg-[#187FC4] text-white rounded-2xl mb-[40px] flex items-center justify-between">
                <p class="ml-[25px] font-bold text-[25px]">Add Purchase Request</p>
                <div class="relative p-5 mr-[5px]">
                    <i id="profileIconBtn"
                        class="fa-solid fa-user cursor-pointer text-xl hover:opacity-80 transition-opacity relative"></i>
                    @include('components.modal_profile')
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <form action="{{ route('purchase_request.index') }}" method="GET" class="flex items-center border border-gray-300 rounded-lg px-3 py-2 w-1/3">
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
                        <a href="{{ route('purchase_request.create') }}"
                            class="px-5 py-2 bg-[#187FC4] text-white rounded-lg hover:bg-[#156ca7] text-sm font-semibold cursor-pointer">
                            Add Purchase Request
                        </a>
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
                                <th class="whitespace-nowrap px-4 py-2 text-left">CURRENCY</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">QUANTITY</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">TOTAL COST</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">CREATED AT</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">SUPPLIER</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">QUOTATION</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">ACTION</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @forelse($pr as $purchase)
                                @php
                                    $detail = $purchase->prDetails;
                                    $status = ucfirst($purchase->status ?? 'pending');
                                    
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
                                    
                                    // Check if editable (only pending and revision can be edited)
                                    $isEditable = in_array(strtolower($purchase->status), ['pending', 'revision']);
                                @endphp
                                <tr class="hover:bg-gray-50" data-pr-id="{{ $purchase->id_pr }}" data-pr-number="{{ $purchase->pr_number }}" data-status="{{ $status }}">
                                    <td class="px-4 py-2">{{ $purchase->pr_number }}</td>
                                    <td class="px-4 py-2">
                                        <span class="{{ $badgeClass }} rounded-full px-3 py-1 text-xs font-semibold">{{ $status }}</span>
                                    </td>
                                    <td class="px-4 py-2">{{ $detail->material_desc ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $detail->uom ?? 'PCS' }}</td>
                                    <td class="px-4 py-2">{{ number_format($detail->unit_price ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">{{ $detail->currency_code ?? 'RP' }}</td>
                                    <td class="px-4 py-2">{{ $detail->quantity ?? 0 }}</td>
                                    <td class="px-4 py-2">{{ number_format($detail->total_cost ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">{{ $purchase->created_at ? $purchase->created_at->format('d-m-Y') : '-' }}</td>
                                    <td class="px-4 py-2">{{ $detail->supplier->name ?? '-' }}</td>
                                    <td class="px-4 py-2">
                                        @if($detail && $detail->quotation_file)
                                            @php
                                                // Try to decode as JSON (multiple files)
                                                $files = json_decode($detail->quotation_file, true);
                                                if (!is_array($files)) {
                                                    // Single file (backward compatible)
                                                    $files = [$detail->quotation_file];
                                                }
                                            @endphp
                                            <div class="flex flex-col gap-1">
                                                @foreach($files as $index => $filename)
                                                    @php
                                                        // Remove timestamp_uniqid_ prefix to show original filename
                                                        // Pattern: timestamp_uniqid_originalname.ext
                                                        $displayName = preg_replace('/^\d+_[a-f0-9]+_/', '', $filename);
                                                    @endphp
                                                    <a href="{{ url('/storage/quotations/' . $filename) }}" 
                                                       target="_blank" 
                                                       class="text-blue-600 hover:underline text-xs">
                                                        <i class="fa-solid fa-file-pdf mr-1"></i>{{ Str::limit($displayName, 25) }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('purchase_request.show', $purchase->id_pr) }}" 
                                               class="bg-[#B6FDF4] text-[#15ADA5] viewBtn p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            @if($isEditable)
                                                <a href="{{ route('purchase_request.edit', $purchase->id_pr) }}" 
                                                   class="bg-[#FFEEB7] text-[#FF8110] editBtn p-2 rounded-lg cursor-pointer hover:bg-[#FBD65E]">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <button type="button" 
                                                        class="bg-[#FFB3BA] text-[#E20030] deleteBtn p-2 rounded-lg cursor-pointer hover:bg-[#FF7C88]"
                                                        data-pr-id="{{ $purchase->id_pr }}"
                                                        data-pr-number="{{ $purchase->pr_number }}"
                                                        onclick="openDeleteModal('{{ $purchase->id_pr }}', '{{ $purchase->pr_number }}')">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            @else
                                                <button class="bg-gray-200 text-gray-400 p-2 rounded-lg cursor-not-allowed" disabled>
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <button class="bg-gray-200 text-gray-400 p-2 rounded-lg cursor-not-allowed" disabled>
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="px-4 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center gap-2">
                                            <i class="fa-solid fa-inbox text-4xl text-gray-300"></i>
                                            <p>No purchase requests found.</p>
                                            <a href="{{ route('purchase_request.create') }}" 
                                               class="text-[#187FC4] hover:underline font-semibold">
                                                Create your first purchase request
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            {{-- No matching results row (hidden by default, shown by JS) --}}
                            <tr id="noResultsRow" style="display: none;">
                                <td colspan="12" class="px-4 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="fa-solid fa-search text-4xl text-gray-300"></i>
                                        <p>No matching results found.</p>
                                        <p class="text-sm">Try adjusting your search or filter criteria.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination Links --}}
                @if($pr->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $pr->links() }}
                    </div>
                @endif
            </div>

            {{-- Filter Modal --}}
            <dialog id="filterModal" class="rounded-lg shadow-lg w-[800px] max-h-[90vh] overflow-hidden p-0 backdrop:bg-black/40">
                <div class="bg-white rounded-lg flex flex-col max-h-[90vh]">
                    <div class="flex justify-between items-center border-b border-gray-300 px-6 py-4">
                        <h1 class="text-2xl font-bold">Filter</h1>
                        <button type="button" id="closeFilterBtn" class="text-gray-500 hover:text-black text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <form action="{{ route('purchase_request.index') }}" method="GET" id="filterForm">
                        <div class="flex-1 overflow-y-auto px-6 py-4">
                            <div class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                                <div><label class="text-sm font-semibold">Status</label>
                                    <select name="status" class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                                        <option value="">All</option>
                                        <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approve" {{ ($filters['status'] ?? '') == 'approve' ? 'selected' : '' }}>Approve</option>
                                        <option value="reject" {{ ($filters['status'] ?? '') == 'reject' ? 'selected' : '' }}>Reject</option>
                                        <option value="revision" {{ ($filters['status'] ?? '') == 'revision' ? 'selected' : '' }}>Revision</option>
                                    </select>
                                </div>
                                <div><label class="text-sm font-semibold">Material Desc</label>
                                    <input type="text" name="material" value="{{ $filters['material'] ?? '' }}" placeholder="e.g. Laptop Lenovo"
                                        class="border w-full border-gray-300 px-3 py-2 rounded-lg placeholder:italic">
                                </div>
                                <div><label class="text-sm font-semibold">Supplier</label>
                                    <input type="text" name="supplier" value="{{ $filters['supplier'] ?? '' }}" placeholder="e.g. CBR Elektronik"
                                        class="border w-full border-gray-300 px-3 py-2 rounded-lg placeholder:italic">
                                </div>
                                <div><label class="text-sm font-semibold">Quantity (>=)</label>
                                    <input type="number" name="quantity" value="{{ $filters['quantity'] ?? '' }}" placeholder="e.g. >= 10"
                                        class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                                </div>
                                <div><label class="text-sm font-semibold">Unit Price (>=)</label>
                                    <input type="number" name="unit_price" value="{{ $filters['unit_price'] ?? '' }}" placeholder="e.g. >= 100000"
                                        class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                                </div>
                                <div><label class="text-sm font-semibold">Total Cost (>=)</label>
                                    <input type="number" name="total_cost" value="{{ $filters['total_cost'] ?? '' }}" placeholder="e.g. >= 1000000"
                                        class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                                </div>
                                <div class="col-span-2"><label class="text-sm font-semibold">Created At (From - To)</label>
                                    <div class="flex gap-6 mt-1">
                                        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                                            class="border w-1/2 border-gray-300 px-3 py-2 rounded-lg">
                                        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                                            class="border w-1/2 border-gray-300 px-3 py-2 rounded-lg">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end gap-6 border-t border-gray-200 px-6 py-4 bg-gray-50">
                            <a href="{{ route('purchase_request.index') }}"
                                class="px-6 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-semibold transition">Reset</a>
                            <button type="submit"
                                class="px-6 py-2 bg-[#187FC4] text-white rounded-lg font-semibold hover:bg-[#156ca7] transition">Apply</button>
                        </div>
                    </form>
                </div>
            </dialog>

            {{-- Delete Confirmation Modal --}}
            <dialog id="deleteModal" class="rounded-2xl shadow-xl p-0 backdrop:bg-black/50 max-w-lg w-full">
                {{-- Header --}}
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-xl font-bold text-gray-800">Delete Purchase Request #<span id="deletePrNumber"></span></h2>
                    <button onclick="closeDeleteModal()" 
                            class="text-gray-400 hover:text-gray-800 text-2xl leading-none">&times;</button>
                </div>
                {{-- Full-width divider --}}
                <div class="border-t border-gray-300"></div>
                {{-- Body --}}
                <div class="px-6 py-8">
                    <p class="text-gray-600 text-base">
                        Are you sure want to delete Purchase Request #<strong id="deletePrNumberBody"></strong>?
                    </p>
                </div>
                {{-- Footer with grey background --}}
                <div class="flex justify-end gap-4 px-6 py-4 bg-gray-100 rounded-b-2xl">
                    <button onclick="closeDeleteModal()" 
                            class="px-8 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-semibold transition">
                        Cancel
                    </button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-8 py-2 bg-[#E20030] text-white rounded-lg font-semibold hover:bg-[#C0002A] transition">
                            Delete
                        </button>
                    </form>
                </div>
            </dialog>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Elements
            const filterModal = document.getElementById('filterModal');
            const filterBtn = document.getElementById('filterBtn');
            const closeFilterBtn = document.getElementById('closeFilterBtn');
            const exportBtn = document.getElementById('exportBtn');

            // Filter Modal - only open/close handling
            filterBtn.addEventListener('click', () => filterModal.showModal());
            closeFilterBtn.addEventListener('click', () => filterModal.close());

            // Export to Excel
            exportBtn.addEventListener('click', () => {
                window.location.href = '{{ route("purchase_request.export") }}';
            });

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const rows = tableBody.querySelectorAll('tr');
                let hasVisibleRows = false;
                
                rows.forEach(row => {
                    if (row.querySelector('td[colspan]') || row.id === 'noResultsRow') return; // Skip empty/noResults row
                    
                    const cells = row.querySelectorAll('td');
                    let match = false;
                    
                    cells.forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(searchTerm)) {
                            match = true;
                        }
                    });
                    
                    row.style.display = match ? '' : 'none';
                    if (match) hasVisibleRows = true;
                });
                
                // Show/hide no results message
                document.getElementById('noResultsRow').style.display = hasVisibleRows ? 'none' : '';
            });
        });

        // Delete modal functions (global scope)
        function openDeleteModal(prId, prNumber) {
            document.getElementById('deletePrNumber').textContent = prNumber;
            document.getElementById('deletePrNumberBody').textContent = prNumber;
            document.getElementById('deleteForm').action = '/purchase-request/' + prId;
            document.getElementById('deleteModal').showModal();
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').close();
        }
    </script>
@endsection