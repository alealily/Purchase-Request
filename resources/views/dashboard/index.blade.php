@extends('layouts.app')

@section('content')
    <div class="flex bg-[#F4F5FA] min-h-screen">
        {{-- Sidebar (putih) --}}
        <aside class="w-64 bg-white h-screen sticky top-0">
            @include('components.it_sidebar')
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 p-10 overflow-hidden">
            <!-- Header -->
            <div class="bg-[#187FC4] text-white rounded-2xl mb-[40px] flex items-center justify-between">
                <p class="ml-[25px] font-bold text-[25px]">Dashboard</p>
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

            <!-- Cards -->
            <div class="grid grid-cols-3 gap-20 mb-[40px] ">
                <div class="bg-white p-6 rounded-2xl transition duration-400 ease-out hover:shadow-lg  hover:scale-101">
                    <p class="text-[20px] text-[gray] font-semibold mb-[5px]">Purchase Request Pending</p>
                    <p class="text-3xl font-bold">8</p>
                </div>
                <div class="bg-white p-6 rounded-2xl transition duration-400 ease-out hover:shadow-lg hover:scale-101">
                    <p class=" text-[20px] text-[gray] font-semibold mb-[5px]">Purchase Request Approve</p>
                    <p class="text-3xl font-bold">5</p>
                </div>
                <div class="bg-white p-6 rounded-2xl transition duration-400 ease-out hover:shadow-lg hover:scale-101">
                    <p class="text-[20px] text-[gray] font-semibold mb-[5px]">Purchase Request Reject</p>
                    <p class="text-3xl font-bold">3</p>
                </div>
            </div>

            <!-- Table -->
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
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">1000020405</td>
                            <td class="px-4 py-4">
                                <span
                                    class="bg-[#FFEEB7] text-[#FF8110] rounded-full px-3 py-1 text-[12px] font-bold text-[12px]">Pending</span>
                            </td>
                            <td class="px-4 py-4">Laptop Acer Aspire 3</td>
                            <td class="px-4 py-4">5</td>
                            <td class="px-4 py-4">Rp12.000.000</td>
                            <td class="px-4 py-4">Rp60.000.000</td>
                            <td class="px-4 py-4">23 Jun 2025</td>
                            <td class="px-4 py-4">Abyan Putra</td>
                            <td class="px-4 py-4">Maintenance</td>
                            <td class="px-4 py-4">PCBA</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">1000020405</td>
                            <td class="px-4 py-4">
                                <span
                                    class="bg-[#FFB3BA] text-[#E20030] rounded-full px-4.5 py-1 text-[12px] font-bold text-[12px]">Reject</span>
                            </td>
                            <td class="px-4 py-4">Laptop Acer Aspire 3</td>
                            <td class="px-4 py-4">5</td>
                            <td class="px-4 py-4">Rp12.000.000</td>
                            <td class="px-4 py-4">Rp60.000.000</td>
                            <td class="px-4 py-4">23 Jun 2025</td>
                            <td class="px-4 py-4">Abyan Putra</td>
                            <td class="px-4 py-4">Maintenance</td>
                            <td class="px-4 py-4">PCBA</td>
                        </tr>
                        </tbody>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">1000020405</td>
                            <td class="px-4 py-4">
                                <span
                                    class="bg-[#B7FCC9] text-[#0A7D0C] rounded-full px-3 py-1 text-[12px] font-bold text-[12px]">Approve</span>
                            </td>
                            <td class="px-4 py-4">Laptop Acer Aspire 3</td>
                            <td class="px-4 py-4">5</td>
                            <td class="px-4 py-4">Rp12.000.000</td>
                            <td class="px-4 py-4">Rp60.000.000</td>
                            <td class="px-4 py-4">23 Jun 2025</td>
                            <td class="px-4 py-4">Abyan Putra</td>
                            <td class="px-4 py-4">Maintenance</td>
                            <td class="px-4 py-4">PCBA</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">1000020405</td>
                            <td class="px-4 py-4">
                                <span
                                    class="bg-[#B7FCC9] text-[#0A7D0C] rounded-full px-3 py-1 text-[12px] font-bold">Approve</span>
                            </td>
                            <td class="px-4 py-4">Laptop Acer Aspire 3</td>
                            <td class="px-4 py-4">5</td>
                            <td class="px-4 py-4">Rp12.000.000</td>
                            <td class="px-4 py-4">Rp60.000.000</td>
                            <td class="px-4 py-4">23 Jun 2025</td>
                            <td class="px-4 py-4">Abyan Putra</td>
                            <td class="px-4 py-4">Maintenance</td>
                            <td class="px-4 py-4">PCBA</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">1000020405</td>
                            <td class="px-4 py-4">
                                <span
                                    class="bg-[#FFB3BA] text-[#E20030] rounded-full px-4.5 py-1 text-[12px] font-bold">Reject</span>
                            </td>
                            <td class="px-4 py-4">Laptop Acer Aspire 3</td>
                            <td class="px-4 py-4">5</td>
                            <td class="px-4 py-4">Rp12.000.000</td>
                            <td class="px-4 py-4">Rp60.000.000</td>
                            <td class="px-4 py-4">23 Jun 2025</td>
                            <td class="px-4 py-4">Abyan Putra</td>
                            <td class="px-4 py-4">Maintenance</td>
                            <td class="px-4 py-4">PCBA</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">1000020405</td>
                            <td class="px-4 py-4">
                                <span
                                    class="bg-[#D9D9D9] text-[#6E6D6D] rounded-full px-3.5 py-1 text-[12px] font-bold">Revised</span>
                            </td>
                            <td class="px-4 py-4">Laptop Acer Aspire 3</td>
                            <td class="px-4 py-4">5</td>
                            <td class="px-4 py-4">Rp12.000.000</td>
                            <td class="px-4 py-4">Rp60.000.000</td>
                            <td class="px-4 py-4">23 Jun 2025</td>
                            <td class="px-4 py-4">Abyan Putra</td>
                            <td class="px-4 py-4">Maintenance</td>
                            <td class="px-4 py-4">PCBA</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">1000020405</td>
                            <td class="px-4 py-4">
                                <span
                                    class="bg-[#FFEEB7] text-[#FF8110] rounded-full px-3 py-1 text-[12px] font-bold">Pending</span>
                            </td>
                            <td class="px-4 py-4">Laptop Acer Aspire 3</td>
                            <td class="px-4 py-4">5</td>
                            <td class="px-4 py-4">Rp12.000.000</td>
                            <td class="px-4 py-4">Rp60.000.000</td>
                            <td class="px-4 py-4">23 Jun 2025</td>
                            <td class="px-4 py-4">Abyan Putra</td>
                            <td class="px-4 py-4">Maintenance</td>
                            <td class="px-4 py-4">PCBA</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">1000020405</td>
                            <td class="px-4 py-4">
                                <span
                                    class="bg-[#D9D9D9] text-[#6E6D6D] rounded-full px-3.5 py-1 text-[12px] font-bold">Revised</span>
                            </td>
                            <td class="px-4 py-4">Laptop Acer Aspire 3</td>
                            <td class="px-4 py-4">5</td>
                            <td class="px-4 py-4">Rp12.000.000</td>
                            <td class="px-4 py-4">Rp60.000.000</td>
                            <td class="px-4 py-4">23 Jun 2025</td>
                            <td class="px-4 py-4">Abyan Putra</td>
                            <td class="px-4 py-4">Maintenance</td>
                            <td class="px-4 py-4">PCBA</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @include('components.modal_profile')
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const profileIconBtn = document.getElementById('profileIconBtn');
                const profileModal = document.getElementById('profileModal');

                // Cek jika elemennya ada di halaman ini
                if (profileIconBtn && profileModal) {

                    // 1. Tampilkan/Sembunyikan modal saat icon diklik
                    profileIconBtn.addEventListener('click', function (event) {
                        event.stopPropagation(); // Mencegah event "klik di luar" terpicu
                        profileModal.classList.toggle('hidden');
                    });

                    // 2. Sembunyikan modal saat klik di luar area modal
                    window.addEventListener('click', function (event) {
                        // Cek jika yang diklik BUKAN modal DAN BUKAN icon
                        if (!profileModal.contains(event.target) && event.target !== profileIconBtn) {
                            profileModal.classList.add('hidden'); // Sembunyikan modal
                        }
                    });
                }
            });
        </script>
@endsection