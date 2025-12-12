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
                    <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2 w-1/3">
                        <i class="fa-solid fa-search text-gray-400 mr-2"></i>
                        <input type="text" id="searchInput" placeholder="Search purchase request"
                            class="w-full focus:outline-none text-sm text-gray-600">
                    </div>

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
                                <th class="whitespace-nowrap px-4 py-2 text-left">CURRENCY</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">UNIT PRICE</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">QUANTITY</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">TOTAL COST</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">CREATED AT</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">SUPPLIER</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">QUOTATION</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">USER</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">DEPARTMENT</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">DIVISION</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left">ACTION</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">1000020405</td>
                                <td class="px-4 py-2">
                                    <span
                                        class="bg-[#FFEEB7] text-[#FF8110] px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                                </td>
                                <td class="px-4 py-2">Scanner Fujitsu 30023</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">90.000</td>
                                <td class="px-4 py-2">2</td>
                                <td class="px-4 py-2">180.000</td>
                                <td class="px-4 py-2">19-02-2025</td>
                                <td class="px-4 py-2">PT. Binba International Persada</td>
                                <td class="px-2 py-2"><a href="{{ asset('/storage/quotations/quotation1.pdf') }}"
                                        target="_blank" class="text-blue-600 hover:underline">Quotation_Scanner</a></td>
                                <td class="px-4 py-2">Abyan Adhiatma</td>
                                <td class="px-4 py-2">Maintenance Engineering</td>
                                <td class="px-4 py-2">PCBA</td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex items-left justify-center gap-2">
                                        <button
                                            class="viewBtn bg-[#B6FDF4] text-[#15ADA5] p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button
                                            class="approveBtn bg-[#B7FCC9] text-[#0A7D0C] w-20 h-10 rounded-lg text-[12px] font-bold cursor-pointer hover:bg-[#51ED79]">Approve</button>
                                        <button
                                            class="rejectBtn bg-[#FFD2D6] text-[#E20030] w-20 h-10 rounded-lg text-[12px] font-bold cursor-pointer hover:bg-[#FB8C96]">Reject</button>
                                        <button
                                            class="revisionBtn bg-[#DFE0FF] text-[#0A0E8D] w-20 h-10 rounded-lg text-[12px] font-bold cursor-pointer hover:bg-[#9C9EFA]">Revisi</button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">1000020406</td>
                                <td class="px-4 py-2">
                                    <span
                                        class="bg-[#FFEEB7] text-[#FF8110] px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                                </td>
                                <td class="px-4 py-2">Laptop Lenovo Thinkpad</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">15.000.000</td>
                                <td class="px-4 py-2">1</td>
                                <td class="px-4 py-2">15.000.000</td>
                                <td class="px-4 py-2">18-02-2025</td>
                                <td class="px-4 py-2">CV. Media Elektronik</td>
                                <td class="px-2 py-2"><a href="{{ asset('/storage/quotations/quotation1.pdf') }}"
                                        target="_blank" class="text-blue-600 hover:underline">Quotation_LaptopLenovo</a>
                                </td>
                                <td class="px-4 py-2">Budi Santoso</td>
                                <td class="px-4 py-2">Purchasing</td>
                                <td class="px-4 py-2">General</td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            class="viewBtn bg-[#B6FDF4] text-[#15ADA5] p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button
                                            class="approveBtn bg-[#B7FCC9] text-[#0A7D0C] w-20 h-10 rounded-lg text-[12px] font-bold cursor-pointer hover:bg-[#51ED79]">Approve</button>
                                        <button
                                            class="rejectBtn bg-[#FFD2D6] text-[#E20030] w-20 h-10 rounded-lg text-[12px] font-bold cursor-pointer hover:bg-[#FB8C96]">Reject</button>
                                        <button
                                            class="revisionBtn bg-[#DFE0FF] text-[#0A0E8D] w-20 h-10 rounded-lg text-[12px] font-bold cursor-pointer hover:bg-[#9C9EFA]">Revisi</button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">1000020407</td>
                                <td class="px-4 py-2">
                                    <span
                                        class="bg-[#D9D9D9] text-[#6E6D6D] px-3 py-1 rounded-full text-xs font-semibold">Revised</span>
                                </td>
                                <td class="px-4 py-2">Kursi Kantor Ergonomis</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">1.200.000</td>
                                <td class="px-4 py-2">5</td>
                                <td class="px-4 py-2">6.000.000</td>
                                <td class="px-4 py-2">17-02-2025</td>
                                <td class="px-4 py-2">PT. Furnitur Jaya</td>
                                <td class="px-2 py-2"><a href="{{ asset('/storage/quotations/quotation1.pdf') }}"
                                        target="_blank" class="text-blue-600 hover:underline">Quotation_Kursi</a></td>
                                <td class="px-4 py-2">Citra Lestari</td>
                                <td class="px-4 py-2">PPIC Molding</td>
                                <td class="px-4 py-2">Molding</td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            class="viewBtn bg-[#B6FDF4] text-[#15ADA5] p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button
                                            class="approveBtn bg-[#B7FCC9] text-[#0A7D0C] w-20 h-10 rounded-lg text-[12px] font-bold cursor-pointer hover:bg-[#51ED79]">Approve</button>
                                        <button
                                            class="rejectBtn bg-[#FFD2D6] text-[#E20030] w-20 h-10 rounded-lg text-[12px] font-bold cursor-pointer hover:bg-[#FB8C96]">Reject</button>
                                        <button
                                            class="revisionBtn bg-[#DFE0FF] text-[#0A0E8D] w-20 h-10 rounded-lg text-[12px] font-bold cursor-pointer hover:bg-[#9C9EFA]">Revisi</button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">1000020408</td>
                                <td class="px-4 py-2">
                                    <span
                                        class="bg-[#DFE0FF] text-[#0A0E8D] px-3 py-1 rounded-full text-xs font-semibold">Revision</span>
                                </td>
                                <td class="px-4 py-2">Keyboard</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">2.000.000</td>
                                <td class="px-4 py-2">1</td>
                                <td class="px-4 py-2">2.000.000</td>
                                <td class="px-4 py-2">04-06-2025</td>
                                <td class="px-4 py-2">Berkah Jaya</td>
                                <td class="px-2 py-2"><a href="{{ asset('/storage/quotations/quotation1.pdf') }}"
                                        target="_blank" class="text-blue-600 hover:underline">Quotation_Keyboard</a></td>
                                <td class="px-4 py-2">Ran Takahashi</td>
                                <td class="px-4 py-2">Engineering Tooling</td>
                                <td class="px-4 py-2">PCBA</td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex items-center justify-left gap-2">
                                        <button
                                            class="viewBtn bg-[#B6FDF4] text-[#15ADA5] cursor-pointer p-2 rounded-lg hover:bg-[#66FFEC]">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button
                                            class="notifyBtn bg-[#FFEEB7] text-[#FF8110] cursor-pointer p-2 rounded-lg hover:bg-[#FBD65E]">
                                            <i class="fa-solid fa-bell"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">1000020408</td>
                                <td class="px-4 py-2">
                                    <span
                                        class="bg-[#FFB3BA] text-[#E20030] px-3 py-1 rounded-full text-xs font-semibold">Reject</span>
                                </td>
                                <td class="px-4 py-2">Monitor Dell 24 inch</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">2.500.000</td>
                                <td class="px-4 py-2">3</td>
                                <td class="px-4 py-2">7.500.000</td>
                                <td class="px-4 py-2">16-02-2025</td>
                                <td class="px-4 py-2">Toko Komputer </td>
                                <td class="px-2 py-2"><a href="{{ asset('/storage/quotations/quotation1.pdf') }}"
                                        target="_blank" class="text-blue-600 hover:underline">Quotation_Monitor</a></td>
                                <td class="px-4 py-2">Abyan Adhiatma</td>
                                <td class="px-4 py-2">Production Engineer</td>
                                <td class="px-4 py-2">PCBA</td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex items-center justify-left gap-2">
                                        <button
                                            class="viewBtn bg-[#B6FDF4] text-[#15ADA5] cursor-pointer p-2 rounded-lg hover:bg-[#66FFEC]">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button
                                            class="notifyBtn bg-[#FFEEB7] text-[#FF8110] cursor-pointer p-2 rounded-lg hover:bg-[#FBD65E]">
                                            <i class="fa-solid fa-bell"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">1000020408</td>
                                <td class="px-4 py-2">
                                    <span
                                        class="bg-[#FFB3BA] text-[#E20030] px-3 py-1 rounded-full text-xs font-semibold">Reject</span>
                                </td>
                                <td class="px-4 py-2">Telephone Center</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">500.000</td>
                                <td class="px-4 py-2">2</td>
                                <td class="px-4 py-2">1.000.000</td>
                                <td class="px-4 py-2">16-02-2025</td>
                                <td class="px-4 py-2">Toko Komputer </td>
                                <td class="px-2 py-2"><a href="{{ asset('/storage/quotations/quotation1.pdf') }}"
                                        target="_blank" class="text-blue-600 hover:underline">Quotation_Monitor</a></td>
                                <td class="px-4 py-2">Abyan Adhiatma</td>
                                <td class="px-4 py-2">Development Testing</td>
                                <td class="px-4 py-2">Assy 2</td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex items-center justify-left gap-2">
                                        <button
                                            class="viewBtn bg-[#B6FDF4] text-[#15ADA5] cursor-pointer p-2 rounded-lg hover:bg-[#66FFEC]">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button
                                            class="notifyBtn bg-[#FFEEB7] text-[#FF8110] cursor-pointer p-2 rounded-lg hover:bg-[#FBD65E]">
                                            <i class="fa-solid fa-bell"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">1000020409</td>
                                <td class="px-4 py-2">
                                    <span
                                        class="bg-[#B7FCC9] text-[#0A7D0C] px-3 py-1 rounded-full text-xs font-semibold">Approve</span>
                                </td>
                                <td class="px-4 py-2">Mouse Robot 3600</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">80.000</td>
                                <td class="px-4 py-2">1</td>
                                <td class="px-4 py-2">80.000</td>
                                <td class="px-4 py-2">15-02-2025</td>
                                <td class="px-4 py-2">CV. Media Elektronik</td>
                                <td class="px-2 py-2"><a href="{{ asset('/storage/quotations/quotation1.pdf') }}"
                                        target="_blank" class="text-blue-600 hover:underline">Quotation_SSD</a></td>
                                <td class="px-4 py-2">Budi Santoso</td>
                                <td class="px-4 py-2">Accounting</td>
                                <td class="px-4 py-2">General</td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex items-center justify-left gap-2">
                                        <button
                                            class="viewBtn bg-[#B6FDF4] text-[#15ADA5] cursor-pointer p-2 rounded-lg hover:bg-[#66FFEC]">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button
                                            class="downloadBtn bg-[#B7E8FF] text-[#187FC4] cursor-pointer p-2 rounded-lg hover:bg-[#6DD0FF]">
                                            <i class="fa-solid fa-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">1000020409</td>
                                <td class="px-4 py-2">
                                    <span
                                        class="bg-[#B7FCC9] text-[#0A7D0C] px-3 py-1 rounded-full text-xs font-semibold">Approve</span>
                                </td>
                                <td class="px-4 py-2">SSD NVMe 1TB</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">1.800.000</td>
                                <td class="px-4 py-2">2</td>
                                <td class="px-4 py-2">3.600.000</td>
                                <td class="px-4 py-2">15-02-2025</td>
                                <td class="px-4 py-2">CV. Media Elektronik</td>
                                <td class="px-2 py-2"><a href="{{ asset('/storage/quotations/quotation1.pdf') }}"
                                        target="_blank" class="text-blue-600 hover:underline">Quotation_SSD</a></td>
                                <td class="px-4 py-2">Budi Santoso</td>
                                <td class="px-4 py-2">IT</td>
                                <td class="px-4 py-2">General</td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex items-center justify-left gap-2">
                                        <button
                                            class="viewBtn bg-[#B6FDF4] text-[#15ADA5] cursor-pointer p-2 rounded-lg hover:bg-[#66FFEC]">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button
                                            class="downloadBtn bg-[#B7E8FF] text-[#187FC4] cursor-pointer p-2 rounded-lg hover:bg-[#6DD0FF]">
                                            <i class="fa-solid fa-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="filterModal" class="hidden fixed inset-0 flex items-center justify-center bg-black/40 z-50">
                    <div class="bg-white rounded-lg shadow-lg w-[800px] max-h-[90vh] overflow-hidden flex flex-col">
                        <div class="flex justify-between items-center border-b border-gray-300 px-6 py-4">
                            <h1 class="text-2xl font-bold">Filter</h1>
                            <button id="closeFilterBtn" class="text-gray-500 hover:text-black text-xl"><i
                                    class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="flex-1 overflow-y-auto px-6 py-4">
                            <form id="filterForm" class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                                <div>
                                    <label class="text-sm font-semibold">Status</label>
                                    <select id="statusFilter"
                                        class="border w-full border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100">
                                        <option value="">All</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Approve">Approve</option>
                                        <option value="Reject">Reject</option>
                                        <option value="Revision">Revision</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-sm font-semibold">Supplier</label>
                                    <input type="text" id="supplierFilter" placeholder="e.g. CBR Elektronik"
                                        class="border w-full border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 placeholder:italic">
                                </div>
                                <div>
                                    <label class="text-sm font-semibold">User</label>
                                    <input type="text" id="userFilter" placeholder="e.g. Abyan Adhiatma"
                                        class="border w-full border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 placeholder:italic">
                                </div>
                                <div>
                                    <label class="text-sm font-semibold">Quantity</label>
                                    <input type="number" id="quantityFilter" placeholder="e.g. >= 10"
                                        class="border w-full border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100">
                                </div>
                                <div>
                                    <label class="text-sm font-semibold">Unit Price</label>
                                    <input type="number" id="unitPriceFilter" placeholder="e.g. <= 100000"
                                        class="border w-full border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100">
                                </div>
                                <div>
                                    <label class="text-sm font-semibold">Total Cost</label>
                                    <input type="number" id="totalCostFilter" placeholder="e.g. >= 1000000"
                                        class="border w-full border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100">
                                </div>
                                <div>
                                    <label class="text-sm font-semibold">Department</label>
                                    <input type="text" id="departmentFilter" placeholder="e.g. Maintenance"
                                        class="border w-full border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 placeholder:italic">
                                </div>
                                <div>
                                    <label class="text-sm font-semibold">Division</label>
                                    <input type="text" id="divisionFilter" placeholder="e.g. Engineering"
                                        class="border w-full border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 placeholder:italic">
                                </div>
                                <div class="col-span-2">
                                    <label class="text-sm font-semibold">Material Desc</label>
                                    <input type="text" id="materialDescFilter" placeholder="e.g. Laptop Lenovo"
                                        class="border w-full border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 placeholder:italic">
                                </div>
                                <div class="col-span-2">
                                    <label class="text-sm font-semibold">Created At (From - To)</label>
                                    <div class="flex gap-6 mt-1">
                                        <input type="date" id="createdFrom"
                                            class="border w-1/2 border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100">
                                        <input type="date" id="createdTo"
                                            class="border w-1/2 border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="flex justify-end gap-6 border-t border-gray-200 px-6 py-4 bg-gray-50">
                            <button id="resetFilter"
                                class="px-6 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-semibold transition">Reset</button>
                            <button id="applyFilter"
                                class="px-6 py-2 bg-[#187FC4] text-white rounded-lg font-semibold hover:bg-[#156ca7] transition">Apply</button>
                        </div>
                    </div>
                </div>

                <div id="detailModal" class="hidden fixed inset-0 flex items-center justify-center bg-black/40 z-50">
                    <div class="bg-white rounded-lg shadow-lg w-[800px] max-h-[90vh] overflow-hidden flex flex-col">
                        <div class="flex justify-between items-center border-b border-gray-300 px-6 py-4">
                            <h2 class="font-bold text-xl">Purchase Request Detail</h2>
                            <button id="closeDetailHeader" class="text-gray-500 hover:text-black text-xl"><i
                                    class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="flex-1 overflow-y-auto px-6 py-4">
                            <div id="detailContent" class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm"></div>
                        </div>
                        <div class="flex justify-end bg-gray-100 mt-6 px-6 py-4 rounded-b-lg">
                            <button id="closeDetail"
                                class="px-8 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 font-semibold transition">Close</button>
                        </div>
                    </div>
                </div>

                <div id="approveModal" class="hidden fixed inset-0 flex items-center justify-center bg-black/40 z-50">
                    <div class="bg-white rounded-lg shadow-lg w-[400px] flex flex-col">
                        <div class="border-b border-gray-300 px-6 py-4 font-bold text-lg text-green-700">
                            Approve Purchase Request
                        </div>
                        <div class="p-6 text-gray-700 text-sm">
                            Are you sure want to approve Purchase Request <strong id="approvePrNumber"
                                class="text-black"></strong>
                        </div>
                        <div class="flex justify-end rounded-lg gap-4 bg-gray-100 px-6 py-4">
                            <button
                                class="cancelBtn px-6 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 font-semibold">Cancel</button>
                            <button
                                class="confirmApprove px-6 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 font-semibold">Approve</button>
                        </div>
                    </div>
                </div>
                <div id="rejectModal" class="hidden fixed inset-0 flex items-center justify-center bg-black/40 z-50">
                    <div class="bg-white rounded-lg shadow-lg w-[450px] flex flex-col">
                        <div class="border-b border-gray-300 px-6 py-4 font-bold text-lg text-red-700">
                            Reject Purchase Request <span id="rejectPrNumber" class="p-2 text-black font-bold"></span>
                        </div>
                        <div class="p-6 text-gray-700">
                            <textarea id="rejectReason" rows="8"
                                class="mt-2 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-100"></textarea>
                        </div>
                        <div class="flex justify-end gap-4 rounded-lg bg-gray-100 px-6 py-4">
                            <button
                                class="cancelBtn px-6 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 font-semibold">Cancel</button>
                            <button
                                class="confirmReject px-6 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 font-semibold">Reject</button>
                        </div>
                    </div>
                </div>
                <div id="revisionModal" class="hidden fixed inset-0 flex items-center justify-center bg-black/40 z-50">
                    <div class="bg-white rounded-lg shadow-lg w-[450px] flex flex-col">
                        <div class="border-b border-gray-300 px-6 py-4 font-bold text-lg text-[#7D4AE3]">
                            Revisi Purchase Request <span id="revisionPrNumber" class="p-2 font-bold text-black "></span>
                        </div>
                        <div class="p-6 text-gray-700">
                            <textarea id="revisionNote" rows="8"
                                class="mt-2 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-100"></textarea>
                        </div>
                        <div class="flex justify-end gap-4 rounded-lg bg-gray-100 px-6 py-4">
                            <button
                                class="cancelBtn px-6 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 font-semibold">Cancel</button>
                            <button
                                class="confirmRevision px-6 py-2 rounded-lg bg-[#945EFF] text-white hover:bg-[#7D4AE3] font-semibold">Revisi</button>
                        </div>
                    </div>
                </div>
                <div id="reasonModal" class="hidden fixed inset-0 flex items-center justify-center bg-black/40 z-50">
                    <div class="bg-white rounded-lg shadow-lg w-[450px] flex flex-col">
                        <div class="flex justify-between items-center border-b border-gray-300 px-6 py-4">
                            <h2 id="reasonModalTitle" class="font-bold text-lg text-gray-800">Notess for PR #</h2>
                            <button class="closeReasonModal text-gray-500 hover:text-black text-xl"><i
                                    class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="p-6 text-gray-700 bg-gray-50 max-h-60 overflow-y-auto">
                            <p id="reasonText" class="text-sm whitespace-pre-wrap"></p>
                        </div>
                        <div class="flex justify-end rounded-lg gap-4 bg-gray-100 px-6 py-4">
                            <button
                                class="closeReasonModal px-6 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 font-semibold">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Deklarasi Variabel
            const searchInput = document.getElementById('searchInput');
            const tableBody = document.getElementById('tableBody');
            const allRows = Array.from(tableBody.querySelectorAll('tr'));
            const filterModal = document.getElementById('filterModal');
            const filterBtn = document.getElementById('filterBtn');
            const closeFilterBtn = document.getElementById('closeFilterBtn');
            const applyFilterBtn = document.getElementById('applyFilter');
            const resetFilterBtn = document.getElementById('resetFilter');
            const detailModal = document.getElementById('detailModal');
            const detailContent = document.getElementById('detailContent');
            const closeDetailHeader = document.getElementById('closeDetailHeader');
            const closeDetail = document.getElementById('closeDetail');
            const approveModal = document.getElementById('approveModal');
            const rejectModal = document.getElementById('rejectModal');
            const revisionModal = document.getElementById('revisionModal');
            const reasonModal = document.getElementById('reasonModal'); // Pastikan modal ini ada di HTML Anda
            let currentRowForAction = null;

            // FUNGSI UNTUK FILTER
            function filterAndSearchRows() {
                const searchText = searchInput.value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value;
                const materialFilter = document.getElementById('materialDescFilter').value.toLowerCase();
                const supplierFilter = document.getElementById('supplierFilter').value.toLowerCase();
                const quantityFilter = parseFloat(document.getElementById('quantityFilter').value);
                const unitPriceFilter = parseFloat(document.getElementById('unitPriceFilter').value);
                const totalCostFilter = parseFloat(document.getElementById('totalCostFilter').value);
                const createdFrom = document.getElementById('createdFrom').value;
                const createdTo = document.getElementById('createdTo').value;
                const userFilter = document.getElementById('userFilter').value.toLowerCase();
                const departmentFilter = document.getElementById('departmentFilter').value.toLowerCase();
                const divisionFilter = document.getElementById('divisionFilter').value.toLowerCase();
                const dateFrom = createdFrom ? new Date(createdFrom) : null;
                const dateTo = createdTo ? new Date(createdTo) : null;
                if (dateFrom) dateFrom.setHours(0, 0, 0, 0);
                if (dateTo) dateTo.setHours(23, 59, 59, 999);
                allRows.forEach(row => {
                    const cells = row.cells;
                    const status = cells[1].textContent.trim();
                    const materialDesc = cells[2].textContent.toLowerCase();
                    const unitPrice = parseFloat(cells[4].textContent.replace(/,/g, ''));
                    const quantity = parseInt(cells[6].textContent.replace(/,/g, ''));
                    const totalCost = parseFloat(cells[7].textContent.replace(/,/g, ''));
                    const dateParts = cells[8].textContent.split('-');
                    const tableDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                    const supplier = cells[9].textContent.toLowerCase();
                    const user = cells[11].textContent.toLowerCase();
                    const department = cells[12].textContent.toLowerCase();
                    const division = cells[13].textContent.toLowerCase();
                    const searchMatch = row.textContent.toLowerCase().includes(searchText);
                    const statusMatch = (statusFilter === "" || status.toLowerCase() === statusFilter.toLowerCase());
                    const materialMatch = materialDesc.includes(materialFilter);
                    const supplierMatch = supplier.includes(supplierFilter);
                    const userMatch = user.includes(userFilter);
                    const departmentMatch = department.includes(departmentFilter);
                    const divisionMatch = division.includes(divisionFilter);
                    const quantityMatch = isNaN(quantityFilter) || quantity >= quantityFilter;
                    const unitPriceMatch = isNaN(unitPriceFilter) || unitPrice <= unitPriceFilter;
                    const totalCostMatch = isNaN(totalCostFilter) || totalCost >= totalCostFilter;
                    const dateMatch = (!dateFrom || tableDate >= dateFrom) && (!dateTo || tableDate <= dateTo);
                    if (searchMatch && statusMatch && materialMatch && supplierMatch && userMatch && departmentMatch && divisionMatch && quantityMatch && unitPriceMatch && totalCostMatch && dateMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // FUNGSI UNTUK EXPORT
            function exportTableToCSV(filename) {
                let csv = [];
                const excludedIndexes = [10, 14];
                const headers = Array.from(document.querySelectorAll('thead th')).filter((_, index) => !excludedIndexes.includes(index)).map(th => `"${th.textContent}"`);
                csv.push(headers.join(','));
                const visibleRows = allRows.filter(row => row.style.display !== 'none');
                for (const row of visibleRows) {
                    const rowData = Array.from(row.cells).filter((_, index) => !excludedIndexes.includes(index)).map(cell => `"${cell.textContent.trim().replace(/"/g, '""')}"`);
                    csv.push(rowData.join(','));
                }
                const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
                const downloadLink = document.createElement('a');
                downloadLink.download = filename;
                downloadLink.href = window.URL.createObjectURL(csvFile);
                downloadLink.style.display = 'none';
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            }

            // FUNGSI LAINNYA
            function updateRowStatus(row, newStatus) {
                const statusCell = row.cells[1];
                const actionCell = row.cells[14];
                const statusSpan = statusCell.querySelector('span');
                let newBadgeClass = '';
                let newActionButtons = '';
                const viewButton = `<button class="viewBtn bg-[#B6FDF4] text-[#15ADA5] p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]"><i class="fa-solid fa-eye"></i></button>`;
                switch (newStatus) {
                    case 'Approve':
                        newBadgeClass = 'bg-[#B7FCC9] text-[#0A7D0C] px-3 py-1 rounded-full text-xs font-semibold';
                        newActionButtons = viewButton + `<button class="downloadBtn bg-[#B7E8FF] text-[#187FC4] cursor-pointer p-2 rounded-lg hover:bg-[#6DD0FF]"><i class="fa-solid fa-download"></i></button>`;
                        break;
                    case 'Reject':
                        newBadgeClass = 'bg-[#FFB3BA] text-[#E20030] px-3 py-1 rounded-full text-xs font-semibold';
                        newActionButtons = viewButton + `<button class="notifyBtn bg-[#FFEEB7] text-[#FF8110] cursor-pointer p-2 rounded-lg hover:bg-[#FBD65E]"><i class="fa-solid fa-bell"></i></button>`;
                        break;
                    case 'Revision':
                        newBadgeClass = 'bg-[#DFE0FF] text-[#0A0E8D] px-3 py-1 rounded-full text-xs font-semibold';
                        newActionButtons = viewButton + `<button class="notifyBtn bg-[#FFEEB7] text-[#FF8110] cursor-pointer p-2 rounded-lg hover:bg-[#FBD65E]"><i class="fa-solid fa-bell"></i></button>`;
                        break;
                }
                statusSpan.textContent = newStatus;
                statusSpan.className = newBadgeClass;
                actionCell.innerHTML = `<div class="flex items-center justify-left gap-2">${newActionButtons}</div>`;
            }
            function showDetailModal(row) {
                const cells = row.cells;
                detailContent.innerHTML = `
            <div>
                <p class="font-semibold text-gray-600">PR Number</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${cells[0].textContent}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-600">Status</p>
                <div class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${cells[1].innerHTML}</div>
            </div>        
            <div>
                <p class="font-semibold text-gray-600">Material Description</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${cells[2].textContent}</p>
            </div>        
            <div>
                <p class="font-semibold text-gray-600">UOM</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${cells[3].textContent}</p>
            </div>        
            <div>
                <p class="font-semibold text-gray-600">Currency</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${cells[5].textContent}</p>
            </div>        
            <div>
                <p class="font-semibold text-gray-600">Unit Price</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${cells[4].textContent}</p>
            </div>        
            <div>
                <p class="font-semibold text-gray-600">Quantity</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${cells[6].textContent}</p>
            </div>        
            <div>
                <p class="font-semibold text-gray-600">Total Cost</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200 font-bold">${cells[7].textContent}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-600">Created At</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${cells[8].textContent}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-600">Supplier</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${cells[9].textContent}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-600">Quotation</p>
                <div class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${cells[10].innerHTML}</div>
            </div>
            <div>
                <p class="font-semibold text-gray-600">Requested by (User)</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${cells[11].textContent}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-600">Department</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${cells[12].textContent}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-600">Division</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${cells[13].textContent}</p>
            </div>
        `;
                detailModal.classList.remove('hidden');
            }

            // EVENT LISTENERS
            searchInput.addEventListener('input', filterAndSearchRows);
            filterBtn.onclick = () => filterModal.classList.remove('hidden');
            closeFilterBtn.onclick = () => filterModal.classList.add('hidden');
            applyFilterBtn.addEventListener('click', () => { filterAndSearchRows(); filterModal.classList.add('hidden'); });
            resetFilterBtn.addEventListener('click', () => { document.getElementById('filterForm').reset(); filterAndSearchRows(); filterModal.classList.add('hidden'); });
            closeDetail.onclick = () => detailModal.classList.add('hidden');
            closeDetailHeader.onclick = () => detailModal.classList.add('hidden');

            tableBody.addEventListener('click', (e) => {
                const button = e.target.closest('button');
                if (!button) return;
                currentRowForAction = button.closest('tr');
                const prNumber = currentRowForAction.cells[0].textContent;
                if (button.classList.contains('approveBtn')) {
                    document.getElementById('approvePrNumber').textContent = '#' + prNumber + '?';
                    approveModal.classList.remove('hidden');
                } else if (button.classList.contains('rejectBtn')) {
                    document.getElementById('rejectPrNumber').textContent = '#' + prNumber;
                    rejectModal.classList.remove('hidden');
                } else if (button.classList.contains('revisionBtn')) {
                    document.getElementById('revisionPrNumber').textContent = '#' + prNumber;
                    revisionModal.classList.remove('hidden');
                } else if (button.classList.contains('viewBtn')) {
                    showDetailModal(currentRowForAction);
                } else if (button.classList.contains('notifyBtn')) {
                    const reason = currentRowForAction.getAttribute('data-reason');
                    const status = currentRowForAction.cells[1].textContent.trim();
                    const reasonModalTitle = document.getElementById('reasonModalTitle');
                    const reasonText = document.getElementById('reasonText');
                    if (status.toLowerCase() === 'reject') {
                        reasonModalTitle.textContent = 'Reasons for Reject PR #' + prNumber;
                    } else {
                        reasonModalTitle.textContent = 'Notes for Revision PR #' + prNumber;
                    }
                    reasonText.textContent = reason || 'Tidak ada catatan yang diberikan.';
                    reasonModal.classList.remove('hidden');
                }
            });

            document.querySelector('.confirmApprove').addEventListener('click', () => { if (currentRowForAction) updateRowStatus(currentRowForAction, 'Approve'); approveModal.classList.add('hidden'); });

            document.querySelector('.confirmReject').addEventListener('click', () => {
                if (currentRowForAction) {
                    const reason = document.getElementById('rejectReason').value;
                    currentRowForAction.setAttribute('data-reason', reason);
                    updateRowStatus(currentRowForAction, 'Reject');
                }
                rejectModal.classList.add('hidden');
                document.getElementById('rejectReason').value = '';
            });

            document.querySelector('.confirmRevision').addEventListener('click', () => {
                if (currentRowForAction) {
                    const note = document.getElementById('revisionNote').value;
                    currentRowForAction.setAttribute('data-reason', note);
                    updateRowStatus(currentRowForAction, 'Revision');
                }
                revisionModal.classList.add('hidden');
                document.getElementById('revisionNote').value = '';
            });

            document.querySelectorAll('.cancelBtn').forEach(btn => {
                btn.onclick = () => {
                    approveModal.classList.add('hidden');
                    rejectModal.classList.add('hidden');
                    revisionModal.classList.add('hidden');
                };
            });

            document.querySelectorAll('.closeReasonModal').forEach(btn => {
                btn.onclick = () => {
                    reasonModal.classList.add('hidden');
                }
            });

            document.getElementById('exportBtn').addEventListener('click', () => exportTableToCSV('purchase_requests.csv'));

            window.addEventListener("click", e => {
                [filterModal, detailModal, approveModal, rejectModal, revisionModal, reasonModal].forEach(modal => {
                    if (modal && e.target === modal) modal.classList.add("hidden");
                });
            });

            // Profile popup is now handled by the modal_profile component
        });
    </script>
@endsection