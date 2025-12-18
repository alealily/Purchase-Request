@extends('layouts.app')

@section('content')
    <div class="flex bg-[#F2F1F1] min-h-screen">
        {{-- Sidebar (dynamic based on role) --}}
        @php
            $userRole = strtolower(auth()->user()->role ?? '');
            $superiorRoles = ['superior', 'head of department', 'head of division', 'president director'];
            $isSuperior = in_array($userRole, $superiorRoles);
            
            // Role colors for badges (case-insensitive keys)
            $roleColorsMap = [
                'employee' => 'bg-[#15ADA5] text-white',
                'it' => 'bg-[#0A7D0C] text-white',
                'head of department' => 'bg-[#FF8110] text-white',
                'head of division' => 'bg-[#155D97] text-white',
                'president director' => 'bg-[#F10000] text-white',
            ];
            
            // Current role filter
            $currentRole = $role ?? 'All';
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
            {{-- Header --}}
            <div class="bg-[#187FC4] text-white rounded-2xl mb-[40px] flex items-center justify-between">
                <p class="ml-[25px] font-bold text-[25px]">User Management</p>
                <div class="relative p-5 mr-[5px]">
                    <i id="profileIconBtn" class="fa-solid fa-user cursor-pointer text-xl hover:opacity-80 transition-opacity relative"></i>
                    @include('components.modal_profile')
                </div>
            </div>

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            {{-- CARD UTAMA --}}
            <div class="bg-white rounded-2xl shadow-md p-6">
                {{-- Role Filter Tabs --}}
                <div class="relative -mx-6 -mt-6 bg-[#F3F7FF] rounded-t-2xl px-6 pt-4">
                    <div class="flex items-center gap-6 border-b border-gray-200">
                        <a href="{{ route('user_management.index') }}" 
                           class="font-semibold text-[#187FC4] pb-3 {{ $currentRole === 'All' || !$currentRole ? 'border-b-[3px] border-[#187FC4]' : '' }}">
                            All User
                        </a>
                        <a href="{{ route('user_management.index', ['role' => 'Employee']) }}" 
                           class="font-semibold text-[#187FC4] pb-3 {{ $currentRole === 'Employee' ? 'border-b-[3px] border-[#187FC4]' : '' }}">
                            Employee
                        </a>
                        <a href="{{ route('user_management.index', ['role' => 'Head of Department']) }}" 
                           class="font-semibold text-[#187FC4] pb-3 {{ $currentRole === 'Head of Department' ? 'border-b-[3px] border-[#187FC4]' : '' }}">
                            Head of Department
                        </a>
                        <a href="{{ route('user_management.index', ['role' => 'Head of Division']) }}" 
                           class="font-semibold text-[#187FC4] pb-3 {{ $currentRole === 'Head of Division' ? 'border-b-[3px] border-[#187FC4]' : '' }}">
                            Head of Division
                        </a>
                        <a href="{{ route('user_management.index', ['role' => 'President Director']) }}" 
                           class="font-semibold text-[#187FC4] pb-3 {{ $currentRole === 'President Director' ? 'border-b-[3px] border-[#187FC4]' : '' }}">
                            President Director
                        </a>
                        <a href="{{ route('user_management.index', ['role' => 'IT']) }}" 
                           class="font-semibold text-[#187FC4] pb-3 {{ $currentRole === 'IT' ? 'border-b-[3px] border-[#187FC4]' : '' }}">
                            IT
                        </a>
                    </div>
                </div>

                {{-- Search and Buttons --}}
                <div class="flex items-center justify-between mb-4 mt-5">
                    <form action="{{ route('user_management.index') }}" method="GET" class="flex items-center border border-gray-300 rounded-lg px-3 py-2 w-1/3">
                        <i class="fa-solid fa-search text-gray-400 mr-2"></i>
                        <input type="text" name="search" placeholder="Search user" value="{{ $search ?? '' }}"
                            class="w-full focus:outline-none text-sm text-gray-600">
                        @if($currentRole)
                            <input type="hidden" name="role" value="{{ $currentRole }}">
                        @endif
                    </form>
                    <div class="flex items-center gap-3">
                        <button id="exportBtn"
                            class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 text-sm font-medium">
                            <i class="fa-solid fa-file-export"></i> Export
                        </button>
                        <a href="{{ route('user_management.create') }}"
                            class="px-5 py-2 bg-[#187FC4] text-white rounded-lg hover:bg-[#156ca7] text-sm font-semibold cursor-pointer">
                            Add User
                        </a>
                    </div>
                </div>

                {{-- Table --}}
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
                        <tbody class="text-sm">
                            @forelse($users as $user)
                                @php
                                    // Get role color (case-insensitive)
                                    $roleLower = strtolower($user->role ?? '');
                                    $badgeClass = $roleColorsMap[$roleLower] ?? 'bg-gray-200 text-gray-700';
                                @endphp
                                <tr class="border-b border-gray-100 hover:bg-gray-50 data-row">
                                    <td class="px-4 py-3">{{ $user->name }}</td>
                                    <td class="px-4 py-3">{{ $user->badge }}</td>
                                    <td class="px-4 py-3">{{ $user->email }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($user->is_active)
                                            <span class="px-3 py-1 bg-[#1ECB57] text-white rounded-lg text-xs font-semibold">Active</span>
                                        @else
                                            <span class="px-3 py-1 bg-[#6E6D6D] text-white rounded-lg text-xs font-semibold">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $user->department ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $user->division ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- View --}}
                                            <a href="{{ route('user_management.show', $user->id_user) }}"
                                                class="bg-[#B6FDF4] text-[#15ADA5] p-2 rounded-lg hover:bg-[#66FFEC]">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            {{-- Edit --}}
                                            <a href="{{ route('user_management.edit', $user->id_user) }}"
                                                class="bg-[#FFEEB7] text-[#FF8110] p-2 rounded-lg hover:bg-[#FBD65E]">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            {{-- Delete --}}
                                            @if($user->id_user !== auth()->id())
                                                <form action="{{ route('user_management.destroy', $user->id_user) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-[#FFB3BA] text-[#E20030] p-2 rounded-lg hover:bg-[#FF7C88]">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="bg-gray-200 text-gray-400 p-2 rounded-lg cursor-not-allowed">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center gap-2">
                                            <i class="fa-solid fa-users text-4xl text-gray-300"></i>
                                            <p>No users found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($users->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        // Export to CSV
        document.getElementById('exportBtn').addEventListener('click', function() {
            const rows = document.querySelectorAll('.data-row');
            let csv = 'Name,Badge,Email,Role,Status,Department,Division\n';
            
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    const cells = row.querySelectorAll('td');
                    const rowData = [
                        cells[0].textContent.trim(),
                        cells[1].textContent.trim(),
                        cells[2].textContent.trim(),
                        cells[3].textContent.trim(),
                        cells[4].textContent.trim(),
                        cells[5].textContent.trim(),
                        cells[6].textContent.trim()
                    ];
                    csv += rowData.map(d => `"${d}"`).join(',') + '\n';
                }
            });

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'users_export.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        });
    </script>
@endsection