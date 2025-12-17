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
                            {{-- Data diambil dari database --}}
                        </tbody>
                    </table>
                </div>
            </div> {{-- END CARD --}}
        </div>

        {{-- Modal Choose Role (modal pertama) --}}
        <div id="roleModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
            <div id="modalContent"
                class="bg-white rounded-2xl shadow-lg w-[90%] max-w-xl px-8 py-6 relative transform scale-95 opacity-0 transition-all duration-300">

                <div class="relative border-b border-gray-200 pb-4 mb-4">
                    <h2 class="text-xl font-bold text-center text-gray-800">Choose Role</h2>
                    <button id="closeModalButton" class="absolute right-0 top-0 text-gray-400 hover:text-gray-600 transition">
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

        <link rel="stylesheet" href="{{ asset('css/modal_choose_role.css') }}">

        {{-- Modal Add/Edit --}}
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
    <script>
        const userManagementConfig = {
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
            apiListUrl: '{{ route("user_management.list") }}'
        };
    </script>
    <script src="{{ asset('js/user_management.js') }}"></script>
    @include('components.modal_profile')
@endsection