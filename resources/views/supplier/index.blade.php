@extends('layouts.app')

@section('content')
    <div class="flex bg-[#F4F5FA] min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-white h-screen sticky top-0">
            @include('components.it_sidebar')
        </aside>

        <div class="flex-1 p-10 overflow-hidden">
            <div class="bg-[#187FC4] text-white rounded-2xl mb-[40px] flex items-center justify-between">
                <p class="ml-[25px] font-bold text-[25px]">Supplier Management</p>
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
                        <input type="text" id="searchInput" placeholder="Search supplier"
                            class="w-full focus:outline-none text-sm text-gray-600">
                    </div>
                    <div class="flex items-center gap-3">
                        <button id="exportBtn"
                            class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 text-sm font-medium">
                            <i class="fa-solid fa-file-export"></i> Export
                        </button>
                        <button id="addSupplierBtn"
                            class="px-5 py-2 bg-[#187FC4] text-white rounded-lg hover:bg-[#156ca7] text-sm font-semibold cursor-pointer">
                            Add Supplier
                        </button>
                    </div>
                </div>

                {{-- Bagian tabel --}}
                <div class="max-w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-[1300px] w-full text-sm text-black">
                        <thead class="bg-gray-100 text-black text-sm uppercase">
                            <tr>
                                <th class="text-left px-4 py-3">Name</th>
                                <th class="text-left px-4 py-3">Address</th>
                                <th class="text-left px-4 py-3">Telephone</th>
                                <th class="text-left px-4 py-3">Email</th>
                                <th class="text-center px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody id="supplierTableBody" class="text-sm">
                            {{-- Data dari JavaScript --}}
                        </tbody>
                    </table>
                </div>
            </div> {{-- END CARD --}}
        </div>

        {{-- Modal Add/Edit Supplier --}}
        <div id="supplierModal" class="hidden fixed inset-0 flex items-center justify-center bg-black/40 z-50">
            <div class="bg-white rounded-lg shadow-lg w-[500px] max-h-[90vh] overflow-hidden flex flex-col"
                id="supplierModalContent">
                <div class="flex justify-between items-center border-b border-gray-300 px-6 py-4">
                    <h2 class="font-bold text-xl" id="modalTitle">Add Supplier</h2>
                    <button class="closeModalBtn text-gray-500 hover:text-black text-xl"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="flex-1 overflow-y-auto px-6 py-4">
                    <form id="supplierForm" class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                        <input type="hidden" id="editingSupplierId">

                        <!-- Name -->
                        <div class="col-span-2">
                            <label class="text-sm font-semibold">Supplier Name</label>
                            <input type="text" id="nameInput" class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                            <p id="nameInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                        <!-- Email -->
                        <div class="col-span-2">
                            <label class="text-sm font-semibold">Email</label>
                            <input type="email" id="emailInput" class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                            <p id="emailInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                        <!-- Telephone -->
                        <div class="col-span-2">
                            <label class="text-sm font-semibold">Telephone</label>
                            <input type="text" id="telephoneInput"
                                class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                            <p id="telephoneInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                        <!-- Address (span 2 cols) -->
                        <div class="col-span-2">
                            <label class="text-sm font-semibold">Address</label>
                            <textarea id="addressInput" rows="3"
                                class="border w-full border-gray-300 px-3 py-2 rounded-lg"></textarea>
                            <p id="addressInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                    </form>
                </div>
                <div class="flex justify-end gap-6 border-t border-gray-200 px-6 py-4 bg-gray-50">
                    <button
                        class="closeModalBtn px-6 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-semibold transition">Cancel</button>
                    <button id="saveSupplierBtn"
                        class="px-6 py-2 bg-[#187FC4] text-white rounded-lg font-semibold hover:bg-[#156ca7] transition">Save
                        Supplier</button>
                </div>
            </div>
        </div>

        {{-- Modal Delete Supplier --}}
        <div id="deleteModal" class="hidden fixed inset-0 flex items-center justify-center bg-black/40 z-50">
            <div class="bg-white rounded-lg shadow-lg w-[400px] overflow-hidden">
                <div class="flex justify-between items-center border-b border-gray-300 px-6 py-4">
                    <h2 class="font-bold text-xl">Delete Supplier</h2>
                    <button class="closeDeleteBtn text-gray-500 hover:text-black text-xl"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="px-6 py-5 text-left">
                    <p class="text-gray-700 text-base mb-2">Are you sure you want to delete this supplier?</p>
                    <p id="deleteSupplierName" class="font-semibold text-gray-900"></p>
                </div>
                <div class="flex justify-end bg-gray-100 px-6 py-4 rounded-b-lg gap-4">
                    <button
                        class="closeDeleteBtn px-6 py-2 bg-white rounded-lg border font-bold cursor-pointer hover:shadow-md transition">Cancel</button>
                    <button id="confirmDeleteBtn"
                        class="px-6 py-2 bg-red-600 text-white rounded-lg font-bold cursor-pointer hover:bg-red-700 transition">Delete</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ▼▼▼ SEMUA SCRIPT DITARUH DI SINI, DI DALAM @section('content') ▼▼▼ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ================== DATA AWAL (SESUAI MOCKUP) ==================
            let suppliers = [{
                id: 1,
                name: "CBR Elektronik Batam Nagoya Hill (Daikin i-Shop Batam)",
                address: "Jl. Teuku Umar Lontai 108 - 109 Lantai UG, Teluk Tering, Batam Kota",
                telephone: "(0778) 4890668",
                email: "cbr@gmail.com"
            }, {
                id: 2,
                name: "Electronic City Nagoya Batam",
                address: "Jl. Teuku Umar Lantai 2, Lubuk Baja Kota, Kec. Lubuk Baja",
                telephone: "(0778) 381214",
                email: "city@gmail.com"
            }, {
                id: 3,
                name: "Toko Cipta Mandiri",
                address: "Jl. Raden Patah No.75 A, B, C, D, Kp. Pelita, Kec. Lubuk Baja, Kota Batam",
                telephone: "(0778) 424222",
                email: "cipta@gmail.com"
            }, {
                id: 4,
                name: "Surga Elektronik Raden Patah",
                address: "Jl. Bunga Raya No.3,4 no 12, Baloi Indah, Kec. Lubuk Baja, Kota Batam",
                telephone: "(0778) 422585",
                email: "raden@gmail.com"
            }, {
                id: 5,
                name: "PT. Bimba international persada distributor elektronik batam",
                address: "Komp. Pertokoan Botania 2 Blok A28 no 7-11, Jalan Raja M. Saleh, Belian",
                telephone: "(0778) 364962",
                email: "bimba@gmail.com"
            }, {
                id: 6,
                name: "Bigge Electronic (i Shop Daikin)",
                address: "Ruko Genta 1, Jl. Brigjen Katamso No.8, Buliang, Batu Aji",
                telephone: "(0778) 5508061",
                email: "bigge@gmail.com"
            }, {
                id: 7,
                name: "CBR Elektronik Batam ( Botania 2 )",
                address: "Ruko Botania, Batam Center, Belian, Kec. Batam Kota",
                telephone: "(0778) 456288",
                email: "cbr@gmail.com"
            }, {
                id: 8,
                name: "Crystal Computer",
                address: "Jl. Pembangunan Blk. A No.4, Batu Selicin, Kec. Lubuk Baja, Kota Batam",
                telephone: "(0778) 4890669", // Nomor asumsi
                email: "crystal@gmail.com"
            }, {
                id: 9,
                name: "Mahkota Jaya Komputerindo",
                address: "Jl. Anggrek Permai, Baloi Indah, Kec. Lubuk Baja, Kota Batam",
                telephone: "(0778) 5508061", // Nomor asumsi
                email: "jaya@gmail.com"
            },];

            let supplierToDeleteId = null;

            // ================== ELEMEN DOM ==================
            const tableBody = document.getElementById('supplierTableBody');
            const searchInput = document.getElementById('searchInput');
            const addSupplierBtn = document.getElementById('addSupplierBtn');
            const exportBtn = document.getElementById('exportBtn');

            // Modal Supplier
            const supplierModal = document.getElementById('supplierModal');
            const supplierModalContent = document.getElementById('supplierModalContent');
            const modalTitle = document.getElementById('modalTitle');
            const supplierForm = document.getElementById('supplierForm');
            const saveSupplierBtn = document.getElementById('saveSupplierBtn');
            const editingSupplierId = document.getElementById('editingSupplierId');

            // Input Fields
            const nameInput = document.getElementById("nameInput");
            const emailInput = document.getElementById("emailInput");
            const telephoneInput = document.getElementById("telephoneInput");
            const addressInput = document.getElementById("addressInput");

            // Delete Modal
            const deleteModal = document.getElementById('deleteModal');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            const deleteSupplierName = document.getElementById('deleteSupplierName');

            // ================== FUNGSI HELPER MODAL ==================

            // Menggunakan fungsi show/hide simpel (tanpa animasi)
            function showModal(dialog) {
                dialog.classList.remove('hidden');
            }

            function hideModal(dialog) {
                dialog.classList.add('hidden');
            }

            // Helper validasi
            const showError = (input, message) => {
                const errorElement = document.getElementById(`${input.id}-error`);
                input.classList.add('border-red-500');
                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.classList.remove('hidden');
                }
            };

            const clearErrors = () => {
                const inputs = [nameInput, emailInput, telephoneInput, addressInput];
                inputs.forEach(input => {
                    if (!input) return;
                    const errorElement = document.getElementById(`${input.id}-error`);
                    input.classList.remove('border-red-500');
                    if (errorElement) {
                        errorElement.textContent = '';
                        errorElement.classList.add('hidden');
                    }
                });
            };

            // ================== FUNGSI RENDER TABEL UTAMA ==================
            function renderTable() {
                tableBody.innerHTML = '';
                const searchText = searchInput.value.toLowerCase();

                const filteredData = suppliers.filter(supplier => {
                    return (
                        supplier.name.toLowerCase().includes(searchText) ||
                        supplier.email.toLowerCase().includes(searchText) ||
                        supplier.telephone.toLowerCase().includes(searchText) ||
                        supplier.address.toLowerCase().includes(searchText)
                    );
                });

                if (filteredData.length === 0) {
                    tableBody.innerHTML =
                        `<tr><td colspan="5" class="text-center p-4 text-gray-500">No data found.</td></tr>`;
                    return;
                }

                filteredData.forEach(supplier => {
                    const row = document.createElement('tr');
                    row.className = 'bg-white hover:bg-gray-50 border-gray-200';
                    row.setAttribute('data-id', supplier.id); // Simpan ID di data attribute

                    row.innerHTML = `
                            <td class="px-4 py-3">${supplier.name}</td>
                            <td class="px-4 py-3">${supplier.address}</td>
                            <td class="px-4 py-3">${supplier.telephone}</td>
                            <td class="px-4 py-3">${supplier.email}</td>
                            <td class="text-center px-4 py-3">
                                <div class="flex justify-center gap-3">
                                    <button class="bg-[#FFEEB7] text-[#FF8110] editBtn p-2 rounded-lg cursor-pointer hover:bg-[#FBD65E]"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button class="bg-[#FFB3BA] text-[#E20030] deleteBtn p-2 rounded-lg cursor-pointer hover:bg-[#FF7C88]"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                            </td>
                        `;
                    tableBody.appendChild(row);
                });
            }

            // ================== FUNGSI MODAL (ADD/EDIT) ==================

            const openAddModal = () => {
                modalTitle.textContent = "Add Supplier";
                saveSupplierBtn.textContent = "Save Supplier";
                supplierForm.reset();
                clearErrors();
                editingSupplierId.value = ""; // Pastikan ID kosong
                showModal(supplierModal);
            };

            const openEditModal = (id) => {
                const supplier = suppliers.find(s => s.id === id);
                if (!supplier) return;

                modalTitle.textContent = "Edit Supplier";
                saveSupplierBtn.textContent = "Save Changes";
                supplierForm.reset();
                clearErrors();

                // Isi form
                editingSupplierId.value = supplier.id;
                nameInput.value = supplier.name;
                emailInput.value = supplier.email;
                telephoneInput.value = supplier.telephone;
                addressInput.value = supplier.address;

                showModal(supplierModal);
            };

            // ================== EVENT LISTENERS ==================

            // Search
            searchInput.addEventListener('input', renderTable);

            // Tombol Add Supplier
            addSupplierBtn.addEventListener('click', openAddModal);

            // Tombol Close Modal (X dan Cancel)
            document.querySelectorAll('.closeModalBtn').forEach(btn => {
                btn.addEventListener('click', () => hideModal(supplierModal));
            });

            // Tombol Save (Add/Edit)
            saveSupplierBtn.addEventListener('click', () => {
                clearErrors();
                let isValid = true;
                const id = editingSupplierId.value;

                // Validasi
                if (nameInput.value.trim() === '') { showError(nameInput, 'Name is required.'); isValid = false; }
                if (emailInput.value.trim() === '') { showError(emailInput, 'Email is required.'); isValid = false; }
                if (telephoneInput.value.trim() === '') { showError(telephoneInput, 'Telephone is required.'); isValid = false; }
                if (addressInput.value.trim() === '') { showError(addressInput, 'Address is required.'); isValid = false; }

                if (!isValid) return;

                const supplierData = {
                    id: id ? parseInt(id) : Date.now(), // Buat ID baru jika add
                    name: nameInput.value.trim(),
                    email: emailInput.value.trim(),
                    telephone: telephoneInput.value.trim(),
                    address: addressInput.value.trim(),
                };

                if (id) {
                    // Mode Edit
                    const index = suppliers.findIndex(s => s.id === parseInt(id));
                    if (index !== -1) {
                        suppliers[index] = supplierData;
                    }
                } else {
                    // Mode Add
                    suppliers.unshift(supplierData); // Tambah ke awal array
                }

                renderTable();
                hideModal(supplierModal);
            });

            // Event delegation untuk tombol Edit dan Delete di tabel
            tableBody.addEventListener('click', (e) => {
                const row = e.target.closest('tr');
                if (!row) return;

                const id = parseInt(row.dataset.id);

                if (e.target.closest('.editBtn')) {
                    openEditModal(id);
                }

                if (e.target.closest('.deleteBtn')) {
                    supplierToDeleteId = id;
                    const supplier = suppliers.find(s => s.id === id);
                    deleteSupplierName.textContent = supplier.name;
                    showModal(deleteModal);
                }
            });

            // Tombol di Modal Delete
            document.querySelectorAll('.closeDeleteBtn').forEach(btn => {
                btn.addEventListener('click', () => hideModal(deleteModal));
            });

            confirmDeleteBtn.addEventListener('click', () => {
                suppliers = suppliers.filter(s => s.id !== supplierToDeleteId);
                renderTable();
                hideModal(deleteModal);
            });

            // Export
            exportBtn.addEventListener('click', () => {
                const searchText = searchInput.value.toLowerCase();
                const dataToExport = suppliers.filter(supplier => {
                    return (
                        supplier.name.toLowerCase().includes(searchText) ||
                        supplier.email.toLowerCase().includes(searchText) ||
                        supplier.telephone.toLowerCase().includes(searchText) ||
                        supplier.address.toLowerCase().includes(searchText)
                    );
                });

                if (dataToExport.length === 0) {
                    alert('No data to export.');
                    return;
                }

                let csv = [];
                const headers = ["NAME", "ADDRESS", "TELEPHONE", "EMAIL"];
                csv.push(headers.join(','));

                dataToExport.forEach(s => {
                    const row = [
                        `"${s.name.replace(/"/g, '""')}"`, // Handle tanda kutip di dalam nama
                        `"${s.address.replace(/"/g, '""')}"`, // Handle tanda kutip di dalam alamat
                        `"${s.telephone}"`,
                        `"${s.email}"`
                    ];
                    csv.push(row.join(','));
                });

                let blob = new Blob([csv.join("\n")], { type: "text/csv;charset=utf-8;" });
                let link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = "supplier_management.csv";
                link.click();
            });

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

            // ================== INISIASI AWAL ==================
            renderTable(); // Tampilkan tabel pertama kali
        });
    </script>

    @include('components.modal_profile')
@endsection