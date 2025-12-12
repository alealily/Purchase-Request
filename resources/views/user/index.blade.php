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

        <div class="flex-1 p-10 overflow-hidden">
            <div class="bg-[#187FC4] text-white rounded-2xl mb-[40px] flex items-center justify-between">
                <p class="ml-[25px] font-bold text-[25px]">User Management</p>
                <div class="relative p-5 mr-[5px]">

                    <i id="profileIconBtn"
                        class="fa-solid fa-user cursor-pointer text-xl hover:opacity-80 transition-opacity relative"></i>

                    @include('components.modal_profile')
                </div>
            </div>

            {{-- CARD UTAMA --}}
            <div class="bg-white rounded-2xl shadow-md p-6">
                {{-- Bagian tab filter --}}
                <div class="relative -mx-6 -mt-6 bg-[#F3F7FF] rounded-t-2xl px-6 pt-4">
                    <div class="flex items-center gap-6 border-gray-200 pb-3">
                        <button class="tab-link active font-semibold text-[#187FC4] border-b-4 border-[#187FC4] pb-2"
                            data-role="All">All User</button>
                        <button class="tab-link font-semibold text-[#187FC4] pb-2" data-role="Employee">Employee</button>
                        <button class="tab-link font-semibold text-[#187FC4] pb-2" data-role="Head of Department">Head of
                            Department</button>
                        <button class="tab-link font-semibold text-[#187FC4] pb-2" data-role="Head of Division">Head of
                            Division</button>
                        <button class="tab-link font-semibold text-[#187FC4] pb-2" data-role="President Director">President
                            Director</button>
                        <button class="tab-link font-semibold text-[#187FC4] pb-2" data-role="IT">IT</button>
                    </div>
                </div>

                {{-- Bagian search dan tombol --}}
                <div class="flex items-center justify-between mb-4 mt-5">
                    <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2 w-1/3">
                        <i class="fa-solid fa-search text-gray-400 mr-2"></i>
                        <input type="text" id="searchInput" placeholder="Search user"
                            class="w-full focus:outline-none text-sm text-gray-600">
                    </div>
                    <div class="flex items-center gap-3">
                        <button id="exportBtn"
                            class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 text-sm font-medium">
                            <i class="fa-solid fa-file-export"></i> Export
                        </button>
                        <button id="addUserBtn"
                            class="px-5 py-2 bg-[#187FC4] text-white rounded-lg hover:bg-[#156ca7] text-sm font-semibold cursor-pointer">
                            Add User
                        </button>
                    </div>
                </div>

                {{-- Bagian tabel --}}
                <div class="max-w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-[1300px] w-full text-sm text-black">
                        <thead class="bg-gray-100 text-black text-sm uppercase">
                            <tr>
                                <th class="text-left px-4 py-3">Name</th>
                                <th class="text-left px-4 py-3">No Badge</th>
                                <th class="text-left px-4 py-3">Email</th>
                                <th class="text-left px-4 py-3">Role</th>
                                <th class="text-left px-4 py-3">Status</th>
                                <th class="text-left px-4 py-3">Department</th>
                                <th class="text-left px-4 py-3">Division</th>
                                <th class="text-center px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody" class="text-sm">
                            {{-- Data dari JavaScript --}}
                        </tbody>
                    </table>
                </div>
            </div> {{-- END CARD --}}
        </div>

        {{-- Modal Choose Role (modal pertama) --}}
        <div id="roleModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
            <div id="modalContent"
                class="bg-white rounded-2xl shadow-lg w-[90%] max-w-xl p-8 relative transform scale-95 opacity-0 transition-all duration-300">

                <div class="flex justify-between items-center border-b pb-3 mb-6">
                    <h2 class="text-lg font-semibold text-center w-full -ml-6">Choose Role</h2>
                    <button id="closeModalButton" class="absolute right-6 top-5 text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <div class="grid grid-cols-3 md:grid-cols-3 gap-4 place-items-center">
                    <div class="role-card" data-role="Employee">
                        <i class="fa-solid fa-users text-3xl mb-2"></i>
                        <p class="text-sm font-semibold">Employee</p>
                    </div>

                    <div class="role-card" data-role="IT">
                        <i class="fa-solid fa-user text-3xl mb-2"></i>
                        <p class="text-sm font-semibold">IT</p>
                    </div>

                    <div class="role-card" data-role="Head of Department">
                        <i class="fa-solid fa-user-tie text-3xl mb-2"></i>
                        <p class="text-sm font-semibold text-center">Head of Department</p>
                    </div>

                    <div class="role-card" data-role="Head of Division">
                        <i class="fa-solid fa-user-tie text-3xl mb-2"></i>
                        <p class="text-sm font-semibold text-center">Head of Division</p>
                    </div>

                    <div class="role-card" data-role="President Director">
                        <i class="fa-solid fa-user-tie text-3xl mb-2"></i>
                        <p class="text-sm font-semibold text-center">President Director</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button id="nextToDetail"
                        class="bg-[#187FC4] text-white px-6 py-2 rounded-lg font-semibold opacity-50 cursor-not-allowed"
                        disabled>
                        Next
                    </button>
                </div>
            </div>
        </div>

        <style>
            .role-card {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                width: 8rem;
                height: 8rem;
                background-color: #fff;
                border: 2px solid #e5e7eb;
                border-radius: 1rem;
                transition: all 0.3s ease;
                cursor: pointer;
            }

            .role-card:hover {
                border-color: #187FC4;
                box-shadow: 0 4px 10px rgba(24, 127, 196, 0.15);
                transform: translateY(-2px);
            }

            .role-card i {
                color: #374151;
            }

            .role-card p {
                color: #111827;
            }

            .role-card.selected {
                border-color: #187FC4;
                background-color: #F3F7FF;
            }

            .role-card.selected i,
            .role-card.selected p {
                color: #187FC4;
            }
        </style>

        {{-- Modal Add/Edit (modal kedua) --}}
        <div id="userModal" class="hidden fixed inset-0 flex items-center justify-center bg-black/40 z-50">
            <div class="bg-white rounded-lg shadow-lg w-[800px] max-h-[90vh] overflow-hidden flex flex-col"
                id="userModalContent">
                <div class="flex justify-between items-center border-b border-gray-300 px-6 py-4">
                    <h2 class="font-bold text-xl" id="modalTitle">Add User</h2>
                    <button class="closeModalBtn text-gray-500 hover:text-black text-xl"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="flex-1 overflow-y-auto px-6 py-4 mb-2">

                    <form id="userForm" class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                        <input type="hidden" id="editingBadge">

                        <div>
                            <label class="text-sm font-semibold">Name</label>
                            <input type="text" id="nameInput" class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                            <p id="nameInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                        <div>
                            <label class="text-sm font-semibold">No Badge</label>
                            <input type="text" id="badgeInput" class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                            <p id="badgeInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                        <div>
                            <label class="text-sm font-semibold">Email</label>
                            <input type="email" id="emailInput" class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                            <p id="emailInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                        <div>
                            <label class="text-sm font-semibold">Role</label>

                            <input type="hidden" id="roleInput" value="">

                            <div id="roleDropdownTrigger" class="relative border w-full border-gray-300 px-3 py-2 rounded-lg bg-white cursor-pointer
                                            flex justify-between items-center">
                                <span id="roleDropdownLabel" class="text-gray-700">-- Select Role --</span>
                                <i class="fa-solid fa-chevron-down text-gray-500 text-xs"></i>
                            </div>

                            <div id="roleDropdownOptions"
                                class="hidden absolute z-10 w-[372px] mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                <div class="p-2 hover:bg-gray-100 cursor-pointer" data-value="Employee">Employee</div>
                                <div class="p-2 hover:bg-gray-100 cursor-pointer" data-value="IT">IT</div>
                                <div class="p-2 hover:bg-gray-100 cursor-pointer" data-value="Head of Department">Head of
                                    Department</div>
                                <div class="p-2 hover:bg-gray-100 cursor-pointer" data-value="Head of Division">Head of
                                    Division</div>
                                <div class="p-2 hover:bg-gray-100 cursor-pointer" data-value="President Director">President
                                    Director</div>
                            </div>

                            <p id="roleInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                        <div>
                            <label class="text-sm font-semibold">Department</label>
                            <input type="text" id="departmentInput"
                                class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                            <p id="departmentInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                        <div>
                            <label class="text-sm font-semibold">Division</label>
                            <input type="text" id="divisionInput"
                                class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                            <p id="divisionInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                        <div id="passwordWrapper">
                            <label class="text-sm font-semibold">Password</label>
                            <input type="password" id="passwordInput"
                                class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                            <p id="passwordInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                        <div id="confirmPasswordWrapper">
                            <label class="text-sm font-semibold">Confirm Password</label>
                            <input type="password" id="confirmPasswordInput"
                                class="border w-full border-gray-300 px-3 py-2 rounded-lg">
                            <p id="confirmPasswordInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                        <div class="col-span-2">
                            <label class="text-sm font-semibold">Signature (JPG/PNG)</label>
                            <input type="file" id="signatureInput"
                                class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded-lg file:bg-gray-100 file:text-black hover:file:bg-gray-200 border border-gray-300 rounded-lg"
                                accept="image/jpeg, image/png">
                            <p id="signatureInput-error" class="text-red-500 text-xs mt-1 hidden"></p>
                            <div id="signaturePreview" class="mt-2 text-xs"></div>
                        </div>

                        <div id="activeCheckboxWrapper" class="col-span-2 hidden flex items-center pt-2">
                            <input type="checkbox" id="isActiveInput"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="isActiveInput" class="ml-2 text-sm font-semibold text-gray-900">User is
                                Active</label>
                        </div>

                    </form>
                </div>
                <div class="flex justify-end gap-6 border-t border-gray-200 px-6 py-4 bg-gray-50">
                    <button
                        class="closeModalBtn px-6 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-semibold transition">Cancel</button>
                    <button id="saveUserBtn"
                        class="px-6 py-2 bg-[#187FC4] text-white rounded-lg font-semibold hover:bg-[#156ca7] transition">Save
                        User</button>
                </div>
            </div>
        </div>

        <div id="deleteModal" class="hidden fixed inset-0 flex items-center justify-center bg-black/40 z-50">
            <div class="bg-white rounded-lg shadow-lg w-[400px] overflow-hidden">
                <div class="flex justify-between items-center border-b border-gray-300 px-6 py-4">
                    <h2 class="font-bold text-xl">Delete User</h2>
                    <button class="closeDeleteBtn text-gray-500 hover:text-black text-xl"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="px-6 py-5 text-left">
                    <p class="text-gray-700 text-base mb-2">Are you sure you want to delete this user?</p>
                    <p id="deleteUserName" class="font-semibold text-gray-900"></p>
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
            let users = [{
                name: "Abyan Putra",
                badge: "0209187",
                email: "abyan.putra@siix-global.com",
                role: "Employee",
                status: "Active",
                dept: "Main Eng",
                division: "PCBA",
                signature: null
            }, {
                name: "Misika Anisa",
                badge: "G19B201",
                email: "miska.anisa@siix-global.com",
                role: "Head of Department",
                status: "Active",
                dept: "PPIC Molding",
                division: "Molding",
                signature: 'misika_sig.png'
            }, {
                name: "Rahayu Wahling",
                badge: "J20C01B",
                email: "rahayu.wahling@siix-global.com",
                role: "Employee",
                status: "Inactive",
                dept: "Accounting",
                division: "General",
                signature: null
            }, {
                name: "Reyna Komila",
                badge: "0209227",
                email: "reyna.komila@siix-global.com",
                role: "Head of Division",
                status: "Active",
                dept: "Testing Development",
                division: "PCBA",
                signature: null
            }, {
                name: "Angga Dwi Aksa",
                badge: "E220012",
                email: "angga.dwi.aksa@siix-global.com",
                role: "Employee",
                status: "Inactive",
                dept: "QA",
                division: "Assy 2",
                signature: null
            }, {
                name: "Pandu Wijaya",
                badge: "G210110",
                email: "pandu.wijaya@siix-global.com",
                role: "President Director",
                status: "Active",
                dept: "-",
                division: "-",
                signature: 'pandu_sig.png'
            }, {
                name: "Agung Prasetya",
                badge: "F19H015",
                email: "agung.prasetya@siix-global.com",
                role: "IT",
                status: "Active",
                dept: "IT",
                division: "General",
                signature: null
            }, {
                name: "Triska Dwkha",
                badge: "0203129",
                email: "triska.dwkha@siix-global.com",
                role: "Head of Department",
                status: "Active",
                dept: "Prod Eng",
                division: "PCBA",
                signature: null
            },];
            let userToDeleteBadge = null;

            // ================== ELEMEN DOM ==================
            const tableBody = document.getElementById('userTableBody');
            const searchInput = document.getElementById('searchInput');
            const tabs = document.querySelectorAll('.tab-link');
            const addUserBtn = document.getElementById('addUserBtn');

            // modal user (detail)
            const userModal = document.getElementById('userModal');
            const userModalContent = document.getElementById('userModalContent');
            const modalTitle = document.getElementById('modalTitle');
            const userForm = document.getElementById('userForm');
            const saveUserBtn = document.getElementById('saveUserBtn');

            // modal choose-role
            const roleModal = document.getElementById('roleModal');
            const modalContent = document.getElementById('modalContent');
            const closeModalButton = document.getElementById('closeModalButton');
            const roleCards = document.querySelectorAll('.role-card');
            const nextToDetail = document.getElementById('nextToDetail');

            // delete modal etc.
            const deleteModal = document.getElementById('deleteModal');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            // form inputs
            const nameInput = document.getElementById("nameInput");
            const badgeInput = document.getElementById("badgeInput");
            const emailInput = document.getElementById("emailInput");

            // ▼▼▼ ELEMEN DROPDOWN KUSTOM BARU ▼▼▼
            const roleInput = document.getElementById("roleInput"); // Input hidden
            const roleDropdownTrigger = document.getElementById("roleDropdownTrigger");
            const roleDropdownLabel = document.getElementById("roleDropdownLabel");
            const roleDropdownOptions = document.getElementById("roleDropdownOptions");
            // ▲▲▲ END ELEMEN KUSTOM ▲▲▲

            const departmentInput = document.getElementById("departmentInput");
            const divisionInput = document.getElementById("divisionInput");
            const passwordInput = document.getElementById("passwordInput");
            const confirmPasswordInput = document.getElementById("confirmPasswordInput");
            const signatureInput = document.getElementById("signatureInput");
            const signaturePreview = document.getElementById("signaturePreview");
            const editingBadgeInput = document.getElementById("editingBadge");

            // wrappers
            const passwordWrapper = document.getElementById("passwordWrapper");
            const confirmPasswordWrapper = document.getElementById("confirmPasswordWrapper");
            const activeCheckboxWrapper = document.getElementById("activeCheckboxWrapper");
            const isActiveInput = document.getElementById("isActiveInput");


            // ================== FUNGSI HELPER ==================
            const getRoleBadge = (role) => {
                switch (role) {
                    case 'Employee':
                        return 'bg-[#15ADA5] text-white';
                    case 'Head of Department':
                        return 'bg-[#FF8110] text-white';
                    case 'Head of Division':
                        return 'bg-[#155D97] text-white';
                    case 'President Director':
                        return 'bg-[#F10000] text-white';
                    case 'IT':
                        return 'bg-[#0A7D0C] text-white';
                    default:
                        return 'bg-gray-200 text-gray-800';
                }
            };

            const getStatusBadge = (status) => {
                if (status === 'Active') return 'bg-[#1ECB57] text-white';
                return 'bg-[#6E6D6D] text-white';
            };

            const showError = (input, message) => {
                // Modifikasi: jika input-nya role, targetkan trigger-nya
                const targetInput = (input.id === 'roleInput') ? roleDropdownTrigger : input;

                const errorElement = document.getElementById(`${input.id}-error`);
                targetInput.classList.add('border-red-500'); // Terapkan border merah ke trigger
                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.classList.remove('hidden');
                }
            };

            const clearErrors = () => {
                // Tambahkan roleDropdownTrigger ke daftar
                const inputs = [nameInput, badgeInput, emailInput, roleInput, roleDropdownTrigger, departmentInput, divisionInput, passwordInput, confirmPasswordInput, signatureInput];
                inputs.forEach(input => {
                    if (!input) return;

                    let errorElement = document.getElementById(`${input.id}-error`);

                    if (!errorElement && input.id === 'roleDropdownTrigger') {
                        errorElement = document.getElementById(`roleInput-error`);
                    }

                    input.classList.remove('border-red-500');

                    if (errorElement) {
                        errorElement.textContent = '';
                        errorElement.classList.add('hidden');
                    }
                });
            };

            // Fungsi baru: Atur field Dept/Division berdasarkan Role
            const toggleDepartmentFields = (role) => {
                const isPresDir = (role === 'President Director');
                departmentInput.disabled = isPresDir;
                divisionInput.disabled = isPresDir;

                const fields = [departmentInput, divisionInput];
                fields.forEach(field => {
                    if (isPresDir) {
                        field.value = '-';
                        field.classList.add('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
                    } else {
                        if (field.value === '-') field.value = '';
                        field.classList.remove('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
                    }
                });
            };

            // ================== FUNGSI RENDER TABEL UTAMA ==================
            function renderTable() {
                tableBody.innerHTML = '';
                const currentFilter = document.querySelector('.tab-link.active').dataset.role;
                const searchText = searchInput.value.toLowerCase();

                const filteredData = users.filter(user => {
                    const roleMatch = (currentFilter === 'All' || user.role === currentFilter);
                    const searchMatch = (
                        user.name.toLowerCase().includes(searchText) ||
                        user.email.toLowerCase().includes(searchText) ||
                        user.badge.toLowerCase().includes(searchText)
                    );
                    return roleMatch && searchMatch;
                });

                if (filteredData.length === 0) {
                    tableBody.innerHTML =
                        `<tr><td colspan="8" class="text-center p-4 text-gray-500">No data found.</td></tr>`;
                    return;
                }

                filteredData.forEach(user => {
                    const row = document.createElement('tr');
                    row.className = 'bg-white hover:bg-gray-50 border-gray-200';
                    row.setAttribute('data-badge', user.badge);
                    row.innerHTML = `
                                    <td class="px-4 py-3">${user.name}</td>
                                    <td class="px-4 py-3">${user.badge}</td>
                                    <td class="px-4 py-3">${user.email}</td>
                                    <td class="px-4 py-3"><span class="px-3 py-1 ${getRoleBadge(user.role)} rounded-full text-xs font-semibold">${user.role}</span></td>
                                    <td class="px-4 py-3"><span class="px-3 py-1 ${getStatusBadge(user.status)} rounded-md text-xs font-semibold">${user.status}</span></td>
                                    <td class="px-4 py-3">${user.dept}</td>
                                    <td class="px-4 py-3">${user.division}</td>
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

            // ================== EVENT LISTENERS ==================

            // Filter Tabs
            tabs.forEach(button => {
                button.addEventListener('click', () => {
                    tabs.forEach(btn => {
                        btn.classList.remove('active', 'border-b-4', 'border-[#187FC4]');
                    });
                    button.classList.add('active', 'border-b-4', 'border-[#187FC4]');
                    renderTable();
                });
            });

            // Search
            searchInput.addEventListener('input', renderTable);

            // ▼▼▼ LOGIKA BARU UNTUK DROPDOWN KUSTOM ▼▼▼

            // 1. Buka/Tutup panel opsi
            roleDropdownTrigger.addEventListener('click', () => {
                // Jangan buka jika 'disabled'
                if (roleDropdownTrigger.classList.contains('disabled')) {
                    return;
                }
                roleDropdownOptions.classList.toggle('hidden');
            });

            // 2. Pilih Opsi
            roleDropdownOptions.addEventListener('click', (e) => {
                if (e.target.dataset.value) {
                    const selectedValue = e.target.dataset.value;
                    const selectedText = e.target.textContent;

                    // Set nilai ke input hidden & label
                    roleInput.value = selectedValue;
                    roleDropdownLabel.textContent = selectedText;

                    // Tutup panel
                    roleDropdownOptions.classList.add('hidden');

                    // Trigger validasi ulang Dept/Division
                    toggleDepartmentFields(selectedValue);

                    // Hapus error jika sebelumnya ada
                    roleDropdownTrigger.classList.remove('border-red-500');
                    document.getElementById('roleInput-error').classList.add('hidden');
                }
            });

            // 3. Klik di luar untuk menutup
            document.addEventListener('click', (e) => {
                if (!roleDropdownTrigger.contains(e.target) && !roleDropdownOptions.contains(e.target)) {
                    roleDropdownOptions.classList.add('hidden');
                }
            });

            // ▲▲▲ END LOGIKA DROPDOWN KUSTOM ▲▲▲


            // ----------------- Modal Choose Role (flow) -----------------

            function showModal(dialog, innerContent) {
                dialog.classList.remove('hidden');
            }

            function hideModal(dialog, innerContent) {
                dialog.classList.add('hidden');
            }

            addUserBtn.addEventListener('click', () => {
                roleCards.forEach(c => c.classList.remove('selected'));
                nextToDetail.disabled = true;
                nextToDetail.classList.add('opacity-50', 'cursor-not-allowed');

                roleModal.classList.remove('hidden');
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 20);
            });

            closeModalButton.addEventListener('click', () => {
                modalContent.classList.add('scale-95', 'opacity-0');
                modalContent.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => {
                    roleModal.classList.add('hidden');
                }, 180);
            });

            let chosenRole = null;
            roleCards.forEach(card => {
                card.addEventListener('click', () => {
                    roleCards.forEach(c => c.classList.remove('selected'));
                    card.classList.add('selected');
                    chosenRole = card.dataset.role;
                    nextToDetail.disabled = false;
                    nextToDetail.classList.remove('opacity-50', 'cursor-not-allowed');
                });
            });

            nextToDetail.addEventListener('click', () => {
                if (!chosenRole) return;

                modalContent.classList.add('scale-95', 'opacity-0');
                modalContent.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => {
                    roleModal.classList.add('hidden');
                }, 180);

                setupAddModal(chosenRole);
                showModal(userModal, userModalContent);
            });

            // ----------------- Modal User (add/edit) -----------------

            // Fungsi baru: Siapkan modal untuk ADD (Diperbarui)
            const setupAddModal = (role) => {
                modalTitle.textContent = "Add User";
                saveUserBtn.textContent = "Save User";
                userForm.reset();
                clearErrors();
                editingBadgeInput.value = "";
                badgeInput.disabled = false;
                badgeInput.classList.remove('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');

                // ▼▼▼ LOGIKA BARU UNTUK DROPDOWN KUSTOM ▼▼▼
                roleInput.value = role; // Set input hidden
                roleDropdownLabel.textContent = role; // Set label yang terlihat
                roleDropdownTrigger.classList.add('disabled'); // Buat jadi abu-abu (CSS kustom)
                // ▲▲▲ END LOGIKA BARU ▲▲▲

                passwordWrapper.classList.remove('hidden');
                confirmPasswordWrapper.classList.remove('hidden');
                passwordInput.placeholder = 'Enter new password';
                confirmPasswordInput.placeholder = 'Confirm new password';
                activeCheckboxWrapper.classList.add('hidden');
                signaturePreview.innerHTML = '';
                toggleDepartmentFields(role); // <-- Panggil ini
            };

            // Fungsi baru: Siapkan modal untuk EDIT (Diperbarui)
            const openEditModal = (badge) => {
                const userData = users.find(u => u.badge === badge);
                if (!userData) return;

                modalTitle.textContent = "Edit User";
                saveUserBtn.textContent = "Save Changes";
                userForm.reset();
                clearErrors();
                editingBadgeInput.value = userData.badge;
                badgeInput.disabled = false;
                badgeInput.classList.remove('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');

                nameInput.value = userData.name;
                badgeInput.value = userData.badge;
                emailInput.value = userData.email;
                departmentInput.value = userData.dept;
                divisionInput.value = userData.division;

                // ▼▼▼ LOGIKA BARU UNTUK DROPDOWN KUSTOM ▼▼▼
                roleInput.value = userData.role; // Set input hidden
                roleDropdownLabel.textContent = userData.role; // Set label
                roleDropdownTrigger.classList.remove('disabled'); // Pastikan bisa di-klik
                // ▲▲▲ END LOGIKA BARU ▲▲▲

                activeCheckboxWrapper.classList.remove('hidden');
                isActiveInput.checked = (userData.status === 'Active');

                passwordWrapper.classList.remove('hidden');
                confirmPasswordWrapper.classList.remove('hidden');
                passwordInput.placeholder = 'Leave blank to keep current password';
                confirmPasswordInput.placeholder = 'Leave blank to keep current password';

                if (userData.signature) {
                    signaturePreview.innerHTML = `<p class="text-gray-600">Current signature: ${userData.signature}</p>`;
                } else {
                    signaturePreview.innerHTML = `<p class="text-gray-500">No signature uploaded.</p>`;
                }

                toggleDepartmentFields(userData.role); // <-- Panggil ini
                showModal(userModal, userModalContent);
            };

            // Tombol close (Cancel / X) untuk modal user (versi simpel)
            document.querySelectorAll('.closeModalBtn').forEach(btn => btn.addEventListener('click', () => {
                hideModal(userModal, userModalContent);
            }));

            // Save user (add or edit) (Diperbarui)
            saveUserBtn.addEventListener('click', () => {
                clearErrors();
                let isValid = true;
                const isEditing = (editingBadgeInput.value !== "");

                // ▼▼▼ LOGIKA BARU ▼▼▼
                // Ambil nilai dari input hidden
                const selectedRole = roleInput.value;
                const newBadge = badgeInput.value.trim();
                const originalBadge = editingBadgeInput.value;
                // ▲▲▲ END LOGIKA BARU ▲▲▲

                if (nameInput.value.trim() === '') { showError(nameInput, 'Name is required.'); isValid = false; }
                if (emailInput.value.trim() === '') { showError(emailInput, 'Email is required.'); isValid = false; }

                // ▼▼▼ Validasi input hidden 'roleInput' ▼▼▼
                if (selectedRole === '') {
                    // Tampilkan error di trigger-nya
                    showError(roleInput, 'Role is required.');
                    isValid = false;
                }

                if (newBadge === '') {
                    showError(badgeInput, 'No Badge is required.');
                    isValid = false;
                } else if (isEditing) {
                    if (newBadge !== originalBadge && users.find(u => u.badge === newBadge)) {
                        showError(badgeInput, 'No Badge already exists.');
                        isValid = false;
                    }
                } else {
                    if (users.find(u => u.badge === newBadge)) {
                        showError(badgeInput, 'No Badge already exists.');
                        isValid = false;
                    }
                }

                if (selectedRole !== 'President Director') {
                    if (departmentInput.value.trim() === '' || departmentInput.value.trim() === '-') {
                        showError(departmentInput, 'Department is required.');
                        isValid = false;
                    }
                    if (divisionInput.value.trim() === '' || divisionInput.value.trim() === '-') {
                        showError(divisionInput, 'Division is required.');
                        isValid = false;
                    }
                }

                const pass = passwordInput.value;
                const confirmPass = confirmPasswordInput.value;

                if (!isEditing) {
                    if (pass === '') { showError(passwordInput, 'Password is required.'); isValid = false; }
                    if (confirmPass === '') { showError(confirmPasswordInput, 'Confirm Password is required.'); isValid = false; }
                }

                if (pass !== '' && pass !== confirmPass) {
                    showError(confirmPasswordInput, 'Passwords do not match.');
                    isValid = false;
                }

                let signatureFilename = null;
                if (signatureInput.files.length > 0) {
                    signatureFilename = signatureInput.files[0].name;
                }

                if (!isValid) return;

                let status = 'Active';
                if (isEditing) {
                    status = isActiveInput.checked ? 'Active' : 'Inactive';
                }

                const userData = {
                    name: nameInput.value.trim(),
                    badge: newBadge,
                    email: emailInput.value.trim(),
                    role: selectedRole,
                    status: status,
                    dept: (selectedRole === 'President Director') ? '-' : departmentInput.value.trim(),
                    division: (selectedRole === 'President Director') ? '-' : divisionInput.value.trim(),
                };

                if (isEditing) {
                    const index = users.findIndex(u => u.badge === originalBadge);
                    if (index !== -1) {
                        const oldSignature = users[index].signature;
                        users[index] = { ...userData, signature: signatureFilename || oldSignature };
                    }
                } else {
                    users.unshift({ ...userData, signature: signatureFilename });
                }

                renderTable();
                hideModal(userModal, userModalContent);
            });

            // Delete Modal
            tableBody.addEventListener('click', (e) => {
                const row = e.target.closest('tr');
                if (!row) return;

                const badgeFromRow = row.dataset.badge;

                if (e.target.closest('.editBtn')) {
                    openEditModal(badgeFromRow);
                }
                if (e.target.closest('.deleteBtn')) {
                    userToDeleteBadge = badgeFromRow;
                    const user = users.find(u => u.badge === userToDeleteBadge);
                    document.getElementById('deleteUserName').textContent = `${user.name} (${user.badge})`;
                    deleteModal.classList.remove('hidden');
                }
            });

            confirmDeleteBtn.addEventListener('click', () => {
                users = users.filter(u => u.badge !== userToDeleteBadge);
                renderTable();
                deleteModal.classList.add('hidden');
            });
            document.querySelectorAll('.closeDeleteBtn').forEach(btn => btn.addEventListener('click', () => deleteModal
                .classList.add('hidden')));

            // Export CSV (Sudah benar)
            const exportBtn = document.getElementById('exportBtn');
            exportBtn.addEventListener('click', () => {
                const currentFilter = document.querySelector('.tab-link.active').dataset.role;
                const searchText = searchInput.value.toLowerCase();
                const dataToExport = users.filter(user => {
                    const roleMatch = (currentFilter === 'All' || user.role === currentFilter);
                    const searchMatch = (
                        user.name.toLowerCase().includes(searchText) ||
                        user.email.toLowerCase().includes(searchText) ||
                        user.badge.toLowerCase().includes(searchText)
                    );
                    return roleMatch && searchMatch;
                });

                if (dataToExport.length === 0) {
                    alert('No data to export.');
                    return;
                }

                let csv = [];
                const headers = ["NAME", "NO BADGE", "EMAIL", "ROLE", "STATUS", "DEPARTMENT", "DIVISION"];
                csv.push(headers.join(','));

                dataToExport.forEach(user => {
                    const row = [
                        `"${user.name}"`, user.badge, user.email, user.role,
                        user.status, `"${user.dept}"`, `"${user.division}"`
                    ];
                    csv.push(row.join(','));
                });

                let blob = new Blob([csv.join("\n")], {
                    type: "text/csv;charset=utf-8;"
                });
                let link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = "user_management.csv";
                link.click();
            });

            // Profile popup is now handled by the modal_profile component

            renderTable(); // Tampilkan tabel pertama kali
        });
    </script>
    @include('components.modal_profile')
@endsection