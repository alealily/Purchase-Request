@extends('layouts.app')

@section('content')
    <div class="flex bg-[#F4F5FA] min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-white h-screen sticky top-0">
            @include('components.it_sidebar')
        </aside>

        <div class="flex-1 p-10 overflow-hidden">
            <div class="bg-[#187FC4] text-white rounded-2xl mb-[40px] flex items-center justify-between">
                <p class="ml-[25px] font-bold text-[25px]">Add Purchase Request</p>
                <div class="relative p-5 mr-[5px]">

                    <i id="profileIconBtn"
                        class="fa-solid fa-user cursor-pointer text-xl hover:opacity-80 transition-opacity relative"></i>

                    <div id="profileModal" class="hidden absolute top-[calc(100%_+_10px)] right-0 
                       bg-white rounded-2xl shadow-xl w-90 z-50 border border-gray-200">

                        <div class="p-6">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-white rounded-full 
                                            border-2 border-gray-300 relative">
                                        <i class="fa-solid fa-user text-gray-500 text-xl
                                              absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-900 truncate">
                                        Abyan Adhiatma
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        abyan.adhiatma@siix-global.com
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5">
                                <a href="{{ route('login') }}" class="block w-full text-center bg-[#187FC4] text-white 
                                      font-bold py-2.5 rounded-lg 
                                      hover:bg-[#156ca7] transition-colors">
                                    LOGOUT
                                </a>
                            </div>
                        </div>
                    </div>
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
                        <button id="addBtn"
                            class="px-5 py-2 bg-[#187FC4] text-white rounded-lg hover:bg-[#156ca7] text-sm font-semibold cursor-pointer">
                            Add Purchase Request
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
                            {{-- Row 1: Pending - Can Edit --}}
                            <tr class="hover:bg-gray-50" data-pr-number="1000020405" data-status="Pending">
                                <td class="px-4 py-2">1000020405</td>
                                <td class="px-4 py-2"><span class="bg-[#FFEEB7] text-[#FF8110] rounded-full px-3 py-1 text-xs font-semibold">Pending</span></td>
                                <td class="px-4 py-2">Klip Binder</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">5,000</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">5</td>
                                <td class="px-4 py-2">25,000</td>
                                <td class="px-4 py-2">25-09-2024</td>
                                <td class="px-4 py-2">CBR Elektronik</td>
                                <td class="px-4 py-2"><a href="{{ url('/storage/quotations/quotation1.pdf') }}" target="_blank" class="text-blue-600 hover:underline">Quotation_KlipBinder.pdf</a></td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <button class="bg-[#B6FDF4] text-[#15ADA5] viewBtn p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]"><i class="fa-solid fa-eye"></i></button>
                                        <button class="bg-[#FFEEB7] text-[#FF8110] editBtn p-2 rounded-lg cursor-pointer hover:bg-[#FBD65E]"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button class="bg-[#FFB3BA] text-[#E20030] deleteBtn p-2 rounded-lg cursor-pointer hover:bg-[#FF7C88]"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Row 2: Approve - Cannot Edit --}}
                            <tr class="hover:bg-gray-50" data-pr-number="1000020406" data-status="Approve">
                                <td class="px-4 py-2">1000020406</td>
                                <td class="px-4 py-2"><span class="bg-[#B7FCC9] text-[#0A7D0C] rounded-full px-3 py-1 text-xs font-semibold">Approve</span></td>
                                <td class="px-4 py-2">Laptop Lenovo ThinkPad</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">12,500,000</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">2</td>
                                <td class="px-4 py-2">25,000,000</td>
                                <td class="px-4 py-2">20-09-2024</td>
                                <td class="px-4 py-2">Batam Supplier</td>
                                <td class="px-4 py-2"><a href="{{ url('/storage/quotations/quotation2.pdf') }}" target="_blank" class="text-blue-600 hover:underline">Quotation_Laptop.pdf</a></td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <button class="bg-[#B6FDF4] text-[#15ADA5] viewBtn p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]"><i class="fa-solid fa-eye"></i></button>
                                        <button class="bg-[#FFEEB7] text-[#FF8110] editBtn p-2 rounded-lg cursor-pointer hover:bg-[#FBD65E]"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button class="bg-[#FFB3BA] text-[#E20030] deleteBtn p-2 rounded-lg cursor-pointer hover:bg-[#FF7C88]"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Row 3: Pending - Can Edit --}}
                            <tr class="hover:bg-gray-50" data-pr-number="1000020407" data-status="Pending">
                                <td class="px-4 py-2">1000020407</td>
                                <td class="px-4 py-2"><span class="bg-[#FFEEB7] text-[#FF8110] rounded-full px-3 py-1 text-xs font-semibold">Pending</span></td>
                                <td class="px-4 py-2">Meja Kantor</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">1,500,000</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">10</td>
                                <td class="px-4 py-2">15,000,000</td>
                                <td class="px-4 py-2">18-09-2024</td>
                                <td class="px-4 py-2">PT Furnitur Jaya</td>
                                <td class="px-4 py-2"><a href="{{ url('/storage/quotations/quotation3.pdf') }}" target="_blank" class="text-blue-600 hover:underline">Quotation_Meja.pdf</a></td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <button class="bg-[#B6FDF4] text-[#15ADA5] viewBtn p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]"><i class="fa-solid fa-eye"></i></button>
                                        <button class="bg-[#FFEEB7] text-[#FF8110] editBtn p-2 rounded-lg cursor-pointer hover:bg-[#FBD65E]"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button class="bg-[#FFB3BA] text-[#E20030] deleteBtn p-2 rounded-lg cursor-pointer hover:bg-[#FF7C88]"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Row 4: Reject - Cannot Edit --}}
                            <tr class="hover:bg-gray-50" data-pr-number="1000020408" data-status="Reject">
                                <td class="px-4 py-2">1000020408</td>
                                <td class="px-4 py-2"><span class="bg-[#FFB3BA] text-[#E20030] rounded-full px-3 py-1 text-xs font-semibold">Reject</span></td>
                                <td class="px-4 py-2">Printer HP LaserJet</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">3,200,000</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">1</td>
                                <td class="px-4 py-2">3,200,000</td>
                                <td class="px-4 py-2">15-09-2024</td>
                                <td class="px-4 py-2">Toko Cipta Mandiri</td>
                                <td class="px-4 py-2"><a href="{{ url('/storage/quotations/quotation4.pdf') }}" target="_blank" class="text-blue-600 hover:underline">Quotation_Printer.pdf</a></td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <button class="bg-[#B6FDF4] text-[#15ADA5] viewBtn p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]"><i class="fa-solid fa-eye"></i></button>
                                        <button class="bg-[#FFEEB7] text-[#FF8110] editBtn p-2 rounded-lg cursor-pointer hover:bg-[#FBD65E]"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button class="bg-[#FFB3BA] text-[#E20030] deleteBtn p-2 rounded-lg cursor-pointer hover:bg-[#FF7C88]"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Row 5: Revision - Cannot Edit --}}
                            <tr class="hover:bg-gray-50" data-pr-number="1000020409" data-status="Revision">
                                <td class="px-4 py-2">1000020409</td>
                                <td class="px-4 py-2"><span class="bg-[#DFE0FF] text-[#0A0E8D] rounded-full px-3 py-1 text-xs font-semibold">Revision</span></td>
                                <td class="px-4 py-2">Kursi Gaming</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">2,800,000</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">3</td>
                                <td class="px-4 py-2">8,400,000</td>
                                <td class="px-4 py-2">12-09-2024</td>
                                <td class="px-4 py-2">PT Furnitur Jaya</td>
                                <td class="px-4 py-2"><a href="{{ url('/storage/quotations/quotation5.pdf') }}" target="_blank" class="text-blue-600 hover:underline">Quotation_Kursi.pdf</a></td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <button class="bg-[#B6FDF4] text-[#15ADA5] viewBtn p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]"><i class="fa-solid fa-eye"></i></button>
                                        <button class="bg-[#FFEEB7] text-[#FF8110] editBtn p-2 rounded-lg cursor-pointer hover:bg-[#FBD65E]"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button class="bg-[#FFB3BA] text-[#E20030] deleteBtn p-2 rounded-lg cursor-pointer hover:bg-[#FF7C88]"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Row 6: Revised - Cannot Edit --}}
                            <tr class="hover:bg-gray-50" data-pr-number="1000020410" data-status="Revised">
                                <td class="px-4 py-2">1000020410</td>
                                <td class="px-4 py-2"><span class="bg-[#D9D9D9] text-[#6E6D6D] rounded-full px-3 py-1 text-xs font-semibold">Revised</span></td>
                                <td class="px-4 py-2">Monitor LG 27 Inch</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">4,500,000</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">5</td>
                                <td class="px-4 py-2">22,500,000</td>
                                <td class="px-4 py-2">10-09-2024</td>
                                <td class="px-4 py-2">CV. Media Elektronik</td>
                                <td class="px-4 py-2"><a href="{{ url('/storage/quotations/quotation6.pdf') }}" target="_blank" class="text-blue-600 hover:underline">Quotation_Monitor.pdf</a></td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <button class="bg-[#B6FDF4] text-[#15ADA5] viewBtn p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]"><i class="fa-solid fa-eye"></i></button>
                                        <button class="bg-[#FFEEB7] text-[#FF8110] editBtn p-2 rounded-lg cursor-pointer hover:bg-[#FBD65E]"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button class="bg-[#FFB3BA] text-[#E20030] deleteBtn p-2 rounded-lg cursor-pointer hover:bg-[#FF7C88]"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </td>
           </tr>

                            {{-- Row 7: Approve - Cannot Edit --}}
                            <tr class="hover:bg-gray-50" data-pr-number="1000020411" data-status="Approve">
                                <td class="px-4 py-2">1000020411</td>
                                <td class="px-4 py-2"><span class="bg-[#B7FCC9] text-[#0A7D0C] rounded-full px-3 py-1 text-xs font-semibold">Approve</span></td>
                                <td class="px-4 py-2">Keyboard Mechanical</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">850,000</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">15</td>
                                <td class="px-4 py-2">12,750,000</td>
                                <td class="px-4 py-2">08-09-2024</td>
                                <td class="px-4 py-2">CBR Elektronik</td>
                                <td class="px-4 py-2"><a href="{{ url('/storage/quotations/quotation7.pdf') }}" target="_blank" class="text-blue-600 hover:underline">Quotation_Keyboard.pdf</a></td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <button class="bg-[#B6FDF4] text-[#15ADA5] viewBtn p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]"><i class="fa-solid fa-eye"></i></button>
                                        <button class="bg-[#FFEEB7] text-[#FF8110] editBtn p-2 rounded-lg cursor-pointer hover:bg-[#FBD65E]"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button class="bg-[#FFB3BA] text-[#E20030] deleteBtn p-2 rounded-lg cursor-pointer hover:bg-[#FF7C88]"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Row 8: Pending - Can Edit --}}
                            <tr class="hover:bg-gray-50" data-pr-number="1000020412" data-status="Pending">
                                <td class="px-4 py-2">1000020412</td>
                                <td class="px-4 py-2"><span class="bg-[#FFEEB7] text-[#FF8110] rounded-full px-3 py-1 text-xs font-semibold">Pending</span></td>
                                <td class="px-4 py-2">Mouse Wireless</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">125,000</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">20</td>
                                <td class="px-4 py-2">2,500,000</td>
                                <td class="px-4 py-2">05-09-2024</td>
                                <td class="px-4 py-2">Toko Cipta Mandiri</td>
                                <td class="px-4 py-2"><a href="{{ url('/storage/quotations/quotation8.pdf') }}" target="_blank" class="text-blue-600 hover:underline">Quotation_Mouse.pdf</a></td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <button class="bg-[#B6FDF4] text-[#15ADA5] viewBtn p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]"><i class="fa-solid fa-eye"></i></button>
                                        <button class="bg-[#FFEEB7] text-[#FF8110] editBtn p-2 rounded-lg cursor-pointer hover:bg-[#FBD65E]"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button class="bg-[#FFB3BA] text-[#E20030] deleteBtn p-2 rounded-lg cursor-pointer hover:bg-[#FF7C88]"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Row 9: Reject - Cannot Edit --}}
                            <tr class="hover:bg-gray-50" data-pr-number="1000020413" data-status="Reject">
                                <td class="px-4 py-2">1000020413</td>
                                <td class="px-4 py-2"><span class="bg-[#FFB3BA] text-[#E20030] rounded-full px-3 py-1 text-xs font-semibold">Reject</span></td>
                                <td class="px-4 py-2">Webcam Logitech</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">1,200,000</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">8</td>
                                <td class="px-4 py-2">9,600,000</td>
                                <td class="px-4 py-2">03-09-2024</td>
                                <td class="px-4 py-2">Batam Supplier</td>
                                <td class="px-4 py-2"><a href="{{ url('/storage/quotations/quotation9.pdf') }}" target="_blank" class="text-blue-600 hover:underline">Quotation_Webcam.pdf</a></td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <button class="bg-[#B6FDF4] text-[#15ADA5] viewBtn p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]"><i class="fa-solid fa-eye"></i></button>
                                        <button class="bg-[#FFEEB7] text-[#FF8110] editBtn p-2 rounded-lg cursor-pointer hover:bg-[#FBD65E]"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button class="bg-[#FFB3BA] text-[#E20030] deleteBtn p-2 rounded-lg cursor-pointer hover:bg-[#FF7C88]"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Row 10: Revision - Cannot Edit --}}
                            <tr class="hover:bg-gray-50" data-pr-number="1000020414" data-status="Revision">
                                <td class="px-4 py-2">1000020414</td>
                                <td class="px-4 py-2"><span class="bg-[#DFE0FF] text-[#0A0E8D] rounded-full px-3 py-1 text-xs font-semibold">Revision</span></td>
                                <td class="px-4 py-2">UPS 1000VA</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">1,800,000</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">6</td>
                                <td class="px-4 py-2">10,800,000</td>
                                <td class="px-4 py-2">01-09-2024</td>
                                <td class="px-4 py-2">CV. Media Elektronik</td>
                                <td class="px-4 py-2"><a href="{{ url('/storage/quotations/quotation10.pdf') }}" target="_blank" class="text-blue-600 hover:underline">Quotation_UPS.pdf</a></td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <button class="bg-[#B6FDF4] text-[#15ADA5] viewBtn p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]"><i class="fa-solid fa-eye"></i></button>
                                        <button class="bg-[#FFEEB7] text-[#FF8110] editBtn p-2 rounded-lg cursor-pointer hover:bg-[#FBD65E]"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button class="bg-[#FFB3BA] text-[#E20030] deleteBtn p-2 rounded-lg cursor-pointer hover:bg-[#FF7C88]"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Row 11: Revised - Cannot Edit --}}
                            <tr class="hover:bg-gray-50" data-pr-number="1000020415" data-status="Revised">
                                <td class="px-4 py-2">1000020415</td>
                                <td class="px-4 py-2"><span class="bg-[#D9D9D9] text-[#6E6D6D] rounded-full px-3 py-1 text-xs font-semibold">Revised</span></td>
                                <td class="px-4 py-2">Headset Noise Cancelling</td>
                                <td class="px-4 py-2">PCS</td>
                                <td class="px-4 py-2">650,000</td>
                                <td class="px-4 py-2">RP</td>
                                <td class="px-4 py-2">12</td>
                                <td class="px-4 py-2">7,800,000</td>
                                <td class="px-4 py-2">28-08-2024</td>
                                <td class="px-4 py-2">CBR Elektronik</td>
                                <td class="px-4 py-2"><a href="{{ url('/storage/quotations/quotation11.pdf') }}" target="_blank" class="text-blue-600 hover:underline">Quotation_Headset.pdf</a></td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <button class="bg-[#B6FDF4] text-[#15ADA5] viewBtn p-2 rounded-lg cursor-pointer hover:bg-[#66FFEC]"><i class="fa-solid fa-eye"></i></button>
                                        <button class="bg-[#FFEEB7] text-[#FF8110] editBtn p-2 rounded-lg cursor-pointer hover:bg-[#FBD65E]"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button class="bg-[#FFB3BA] text-[#E20030] deleteBtn p-2 rounded-lg cursor-pointer hover:bg-[#FF7C88]"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
                    <div class="flex-1 overflow-y-auto px-6 py-4">
                        <form id="filterForm" class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                            <div><label class="text-sm font-semibold">Status</label><select id="statusFilter"
                                    class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                                    <option value="">All</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Approve">Approve</option>
                                    <option value="Reject">Reject</option>
                                    <option value="Revision">Revision</option>
                                    <option value="Revised">Revised</option>
                                </select></div>
                            <div><label class="text-sm font-semibold">Material Desc</label><input type="text"
                                    id="materialDescFilter" placeholder="e.g. Laptop Lenovo"
                                    class="border w-full border-gray-300 px-3 py-2 rounded-lg placeholder:italic"></div>
                            <div><label class="text-sm font-semibold">Supplier</label><input type="text" id="supplierFilter"
                                    placeholder="e.g. CBR Elektronik"
                                    class="border w-full border-gray-300 px-3 py-2 rounded-lg placeholder:italic"></div>
                            <div><label class="text-sm font-semibold">Quantity</label><input type="number"
                                    id="quantityFilter" placeholder="e.g. >= 10"
                                    class="border w-full border-gray-300 px-3 py-2 rounded-lg"></div>
                            <div><label class="text-sm font-semibold">Unit Price</label><input type="number"
                                    id="unitPriceFilter" placeholder="e.g. <= 100000"
                                    class="border w-full border-gray-300 px-3 py-2 rounded-lg"></div>
                            <div><label class="text-sm font-semibold">Total Cost</label><input type="number"
                                    id="totalCostFilter" placeholder="e.g. >= 1000000"
                                    class="border w-full border-gray-300 px-3 py-2 rounded-lg"></div>
                            <div class="col-span-2"><label class="text-sm font-semibold">Created At (From - To)</label>
                                <div class="flex gap-6 mt-1"><input type="date" id="createdFrom"
                                        class="border w-1/2 border-gray-300 px-3 py-2 rounded-lg"><input type="date"
                                        id="createdTo" class="border w-1/2 border-gray-300 px-3 py-2 rounded-lg"></div>
                            </div>
                        </form>
                    </div>
                    <div class="flex justify-end gap-6 border-t border-gray-200 px-6 py-4 bg-gray-50">
                        <button type="button" id="resetFilter"
                            class="px-6 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-semibold transition">Reset</button>
                        <button type="button" id="applyFilter"
                            class="px-6 py-2 bg-[#187FC4] text-white rounded-lg font-semibold hover:bg-[#156ca7] transition">Apply</button>
                    </div>
                </div>
            </dialog>

            {{-- Form Modal (Add/Edit) --}}
            <dialog id="formModal" class="rounded-lg shadow-lg w-[800px] max-h-[90vh] overflow-hidden p-0 backdrop:bg-black/40">
                <div class="bg-white rounded-lg flex flex-col max-h-[90vh]">
                    <div class="flex justify-between items-center border-b border-gray-300 px-6 py-4">
                        <h2 class="font-bold text-xl" id="modalTitle">Add Purchase Request</h2>
                        <button type="button" class="closeFormBtn text-gray-500 hover:text-black text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto px-6 py-4">
                        <form id="purchaseForm" class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                            <input type="hidden" id="editingPrNumber">
                            <div>
                                <label class="text-sm font-semibold">Material Description</label>
                                <input type="text" id="materialInput"
                                    class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                                <p id="materialInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold">UOM</label>
                                <input type="text" value="PCS" disabled
                                    class="w-full border-gray-300 px-3 py-2 rounded bg-gray-100 text-gray-500">
                            </div>
                            <div>
                                <label class="text-sm font-semibold">Unit Price</label>
                                <input type="number" id="unitPriceInput"
                                    class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                                <p id="unitPriceInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold">Currency</label>
                                <input type="text" value="RP" disabled
                                    class="w-full border-gray-300 px-3 py-2 rounded bg-gray-100 text-gray-500">
                            </div>
                            <div>
                                <label class="text-sm font-semibold">Quantity</label>
                                <input type="number" id="quantityInput"
                                    class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                                <p id="quantityInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold">Total Cost</label>
                                <input type="text" id="totalCostInput" readonly
                                    class="w-full border-gray-300 px-3 py-2 rounded bg-gray-100 font-bold">
                            </div>
                            <div>
                                <label class="text-sm font-semibold">Supplier</label>
                                <select id="supplierInput" class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                                    <option value="">-- Select Supplier --</option>
                                    <option value="CBR Elektronik">CBR Elektronik</option>
                                    <option value="Batam Supplier">Batam Supplier</option>
                                    <option value="Toko Cipta Mandiri">Toko Cipta Mandiri</option>
                                    <option value="CV. Media Elektronik">CV. Media Elektronik</option>
                                    <option value="PT Furnitur Jaya">PT Furnitur Jaya</option>
                                </select>
                                <p id="supplierInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold">Quotation (PDF, DOCX)</label>
                                <input type="file" id="quotationInput" accept=".pdf,.docx"
                                    class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded-lg file:bg-gray-100 file:text-black hover:file:bg-gray-200 border border-gray-300 rounded-lg">
                                <p id="quotationInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                            </div>
                        </form>
                    </div>
                    <div class="flex justify-end gap-6 border-t border-gray-200 px-6 py-4 bg-gray-50">
                        <button type="button"
                            class="closeFormBtn px-6 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-semibold transition">Cancel</button>
                        <button type="button" id="saveForm"
                            class="px-6 py-2 bg-[#187FC4] text-white rounded-lg font-semibold hover:bg-[#156ca7] transition">Save</button>
                    </div>
                </div>
            </dialog>

            {{-- Detail Modal --}}
            <dialog id="detailModal" class="rounded-lg shadow-lg w-[800px] max-h-[90vh] overflow-hidden p-0 backdrop:bg-black/40">
                <div class="bg-white rounded-lg flex flex-col max-h-[90vh]">
                    <div class="flex justify-between items-center border-b border-gray-300 px-6 py-4">
                        <h2 class="font-bold text-xl">Purchase Request Detail</h2>
                        <button type="button" class="closeDetailBtn text-gray-500 hover:text-black text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto px-6 py-4">
                        <div id="detailContent" class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm"></div>
                    </div>
                    <div class="flex justify-end bg-gray-100 mt-6 px-6 py-4 rounded-b-lg">
                        <button type="button"
                            class="closeDetailBtn px-8 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 font-semibold transition">Close</button>
                    </div>
                </div>
            </dialog>

            {{-- Delete Confirmation Modal --}}
            <dialog id="deleteModal" class="rounded-lg shadow-lg w-[400px] overflow-hidden p-0 backdrop:bg-black/40">
                <div class="bg-white rounded-lg">
                    <div class="flex justify-between items-center border-b border-gray-300 px-6 py-4">
                        <h2 class="font-bold text-xl">Delete Purchase Request</h2>
                        <button type="button" class="closeDeleteBtn text-gray-500 hover:text-black text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="px-6 py-5 text-left">
                        <p class="text-gray-700 text-base mb-2">Are you sure?</p>
                        <p id="deleteItemName" class="font-semibold text-gray-900"></p>
                    </div>
                    <div class="flex justify-end bg-gray-100 px-6 py-4 rounded-b-lg gap-4">
                        <button type="button"
                            class="closeDeleteBtn px-6 py-2 bg-white rounded-lg border font-bold cursor-pointer hover:shadow-md transition">Cancel</button>
                        <button type="button" id="confirmDelete"
                            class="px-6 py-2 bg-red-600 text-white rounded-lg font-bold cursor-pointer hover:bg-red-700 transition">Delete</button>
                    </div>
                </div>
            </dialog>

            {{-- Restricted Action Modal --}}
            <dialog id="restrictedModal" class="rounded-lg shadow-lg w-[550px] overflow-hidden p-0 backdrop:bg-black/40">
                <div class="bg-white rounded-lg">
                    <div class="flex justify-between items-center border-b border-gray-300 px-6 py-4">
                        <h2 class="font-bold text-xl">Action Restricted</h2>
                        <button type="button" class="closeRestrictedBtn text-gray-500 hover:text-black text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="px-6 py-8 text-left">
                        <p class="text-gray-700 text-base mb-2" id="restrictedMessage"></p>
                    </div>
                </div>
            </dialog>
        </div>
    </div>

    <script>
        const BASE_URL = "{{ url('/') }}";
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ================== PENGATURAN AWAL==================
            const tableBody = document.getElementById('tableBody');
            const searchInput = document.getElementById('searchInput');
            


            function getStatusInfo(status) {
                switch (status.toLowerCase()) {
                    case 'pending': 
                        return { classes: 'bg-[#FFEEB7] text-[#FF8110] rounded-full px-3 py-1 text-xs font-semibold' };
                    case 'approve': 
                        return { classes: 'bg-[#B7FCC9] text-[#0A7D0C] rounded-full px-3 py-1 text-xs font-semibold' };
                    case 'reject': 
                        return { classes: 'bg-[#FFB3BA] text-[#E20030] rounded-full px-3 py-1 text-xs font-semibold' };
                    case 'revision': 
                        return { classes: 'bg-[#DFE0FF] text-[#0A0E8D] rounded-full px-3 py-1 text-xs font-semibold' };
                    case 'revised': 
                        return { classes: 'bg-[#D9D9D9] text-[#6E6D6D] rounded-full px-3 py-1 text-xs font-semibold' };
                    default:
                        return { classes: 'bg-gray-200 text-gray-700 rounded-full px-3 py-1 text-xs font-semibold' };
                }
            }

            // --- FUNGSI BARU UNTUK VALIDASI ---
            function showError(inputElement, message) {
                const errorElement = document.getElementById(`${inputElement.id}-error`);
                inputElement.classList.add('border-red-500');
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            }

            function clearErrors() {
                const inputs = [materialInput, unitPriceInput, quantityInput, supplierInput, quotationInput];
                inputs.forEach(input => {
                    const errorElement = document.getElementById(`${input.id}-error`);
                    input.classList.remove('border-red-500');
                    if (errorElement) {
                        errorElement.textContent = '';
                        errorElement.classList.add('hidden');
                    }
                });
            }



            // ================== SEMUA MODAL & EVENT LISTENERS ==================
            const formModal = document.getElementById("formModal");
            const modalTitle = document.getElementById("modalTitle");
            const addBtn = document.getElementById("addBtn");
            const saveForm = document.getElementById("saveForm");
            const materialInput = document.getElementById("materialInput");
            const unitPriceInput = document.getElementById("unitPriceInput");
            const quantityInput = document.getElementById("quantityInput");
            const totalCostInput = document.getElementById("totalCostInput");
            const supplierInput = document.getElementById("supplierInput");
            const quotationInput = document.getElementById("quotationInput");
            const editingPrNumberInput = document.getElementById("editingPrNumber");
            const detailModal = document.getElementById("detailModal");
            const detailContent = document.getElementById("detailContent");
            const deleteModal = document.getElementById("deleteModal");
            const confirmDelete = document.getElementById("confirmDelete");
            const deleteItemName = document.getElementById("deleteItemName");
            const restrictedModal = document.getElementById("restrictedModal");
            const restrictedMessage = document.getElementById("restrictedMessage");
            const filterBtn = document.getElementById("filterBtn");
            const exportBtn = document.getElementById("exportBtn");
            const closeFilterBtn = document.getElementById("closeFilterBtn");
            const applyFilter = document.getElementById("applyFilter");
            const resetFilter = document.getElementById("resetFilter");
            let prNumberToDelete = null;

            function calculateTotalCost() {
                const price = parseFloat(unitPriceInput.value) || 0;
                const qty = parseInt(quantityInput.value) || 0;
                totalCostInput.value = (price * qty).toLocaleString("id-ID");
            }
            unitPriceInput.addEventListener('input', calculateTotalCost);
            quantityInput.addEventListener('input', calculateTotalCost);

            addBtn.addEventListener('click', () => {
                document.getElementById('purchaseForm').reset();
                modalTitle.textContent = "Add Purchase Request";
                editingPrNumberInput.value = "";
                calculateTotalCost();
                clearErrors();
                formModal.showModal();
            });
            document.querySelectorAll('.closeFormBtn').forEach(btn => btn.addEventListener('click', () => formModal.close()));



            function showDetailModal(prNumber) {
                // Ambil data dari row HTML yang diklik
                const row = tableBody.querySelector(`tr[data-pr-number="${prNumber}"]`);
                if (!row) return;
                
                const cells = row.querySelectorAll('td');
                const prData = {
                    prNumber: cells[0].textContent,
                    status: row.getAttribute('data-status'),
                    materialDesc: cells[2].textContent,
                    uom: cells[3].textContent,
                    unitPrice: cells[4].textContent,
                    currency: cells[5].textContent,
                    quantity: cells[6].textContent,
                    totalCost: cells[7].textContent,
                    createdAt: cells[8].textContent,
                    supplier: cells[9].textContent,
                    quotationFile: cells[10].querySelector('a') ? cells[10].querySelector('a').textContent : 'N/A',
                    quotationPath: cells[10].querySelector('a') ? cells[10].querySelector('a').href : '#'
                };
                
                const statusInfo = getStatusInfo(prData.status);
                detailContent.innerHTML = `
            <div>
                <p class="font-semibold text-gray-600">PR Number</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${prData.prNumber}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-600">Status</p>
                <div class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">
                    <span class="${statusInfo.classes}">${prData.status}</span>
                </div>
            </div>
            <div>
                <p class="font-semibold text-gray-600">Material Description</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${prData.materialDesc}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-600">UOM</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${prData.uom}</p>
            </div>        
            <div>
                <p class="font-semibold text-gray-600">Currency</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${prData.currency}</p>
            </div>        
            <div>
                <p class="font-semibold text-gray-600">Unit Price</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${prData.unitPrice}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-600">Quantity</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${prData.quantity}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-600">Total Cost</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200 font-bold">${prData.totalCost}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-600">Created At</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${prData.createdAt}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-600">Supplier</p>
                <p class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">${prData.supplier}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-600">Quotation</p>
                <div class="mt-1 rounded-lg px-3 py-2 text-gray-600 bg-gray-50 border border-gray-200">
                    <a href="${prData.quotationPath}" target="_blank" class="text-blue-600 hover:underline">${prData.quotationFile}</a>
                </div>
            </div>
        `;
                detailModal.showModal();
                document.body.style.overflow = 'hidden'; // Disable background scroll
            }
            
            // Helper functions untuk mengontrol body scroll
            function disableBodyScroll() {
                document.body.style.overflow = 'hidden';
            }
            
            function enableBodyScroll() {
                document.body.style.overflow = '';
            }
            
            // Event listeners untuk detail modal
            document.querySelectorAll('.closeDetailBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    detailModal.close();
                    enableBodyScroll();
                });
            });


            document.querySelectorAll('.closeDeleteBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    deleteModal.close();
                    enableBodyScroll();
                });
            });

            function showRestrictedModal(action, prNumber) {
                const modalTitle = action === 'edit' ? 'Edit Purchase Request' : 'Delete Purchase Request';
                const modalMessage = action === 'edit' 
                    ? `Cannot edit Purchase Request <b>#${prNumber}</b>` 
                    : `Cannot delete Purchase Request <b>#${prNumber}</b>`;
                
                document.querySelector('#restrictedModal h2').textContent = `${modalTitle} #${prNumber}`;
                restrictedMessage.innerHTML = modalMessage;
                restrictedModal.showModal();
                disableBodyScroll();
            }
            
            document.querySelectorAll('.closeRestrictedBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    restrictedModal.close();
                    enableBodyScroll();
                });
            });

            tableBody.addEventListener('click', (e) => {
                const button = e.target.closest('button');
                if (!button) return;
                const row = button.closest('tr');
                if (!row || !row.dataset.prNumber) return;
                const prNumber = row.dataset.prNumber;
                const status = row.getAttribute('data-status');

                if (button.classList.contains('viewBtn')) {
                    showDetailModal(prNumber);
                } else if (button.classList.contains('editBtn')) {
                    // Cek apakah status adalah pending atau revision
                    if (status.toLowerCase() !== 'pending' && status.toLowerCase() !== 'revision') { 
                        showRestrictedModal('edit', prNumber); 
                        return; 
                    }
                    alert('Fitur edit akan diaktifkan setelah terintegrasi dengan backend API');
                } else if (button.classList.contains('deleteBtn')) {
                    // Cek apakah status adalah pending atau revision
                    if (status.toLowerCase() !== 'pending' && status.toLowerCase() !== 'revision') { 
                        showRestrictedModal('delete', prNumber); 
                        return; 
                    }
                    prNumberToDelete = prNumber;
                    deleteItemName.textContent = `PR Number: ${prNumber}`;
                    deleteModal.showModal();
                    disableBodyScroll();
                }
            });
            
            // Handle backdrop click dan ESC key untuk restore scroll
            [detailModal, deleteModal, restrictedModal].forEach(modal => {
                // Handle klik di backdrop (area di luar modal content)
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.close();
                        enableBodyScroll();
                    }
                });
                
                // Handle saat modal ditutup (termasuk ESC key)
                modal.addEventListener('close', () => {
                    enableBodyScroll();
                });
            });

            filterBtn.addEventListener('click', () => filterModal.showModal());
            closeFilterBtn.addEventListener('click', () => filterModal.close());
            // NOTE: Filter/search dinonaktifkan sementara untuk static HTML
            applyFilter.addEventListener('click', () => { 
                alert('Fitur filter akan diaktifkan setelah terintegrasi dengan backend API');
                filterModal.close(); 
            });
            resetFilter.addEventListener('click', () => { 
                document.getElementById("filterForm").reset(); 
                filterModal.close(); 
            });

            // Export to Excel functionality
            exportBtn.addEventListener('click', () => {
                // Redirect to export route which will download the Excel file
                window.location.href = '{{ route("purchase_request.export") }}';
            });

            const profileIconBtn = document.getElementById('profileIconBtn');
            const profileModal = document.getElementById('profileModal');

            if (profileIconBtn && profileModal) {
                profileIconBtn.addEventListener('click', function (event) {
                    event.stopPropagation();
                    profileModal.classList.toggle('hidden');
                });

                window.addEventListener('click', function (event) {
                    if (!profileModal.contains(event.target) && event.target !== profileIconBtn) {
                        profileModal.classList.add('hidden');
                    }
                });
            }

            // NOTE: Search tidak berfungsi untuk static  HTML, fitur ini akan dikembalikan saat integrasi API
            // searchInput.addEventListener('input', renderTable);
        });
    </script>
@endsection