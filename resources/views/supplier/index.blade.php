@extends('layouts.app')

@section('title', 'Supplier Management')

@section('content')
    <div class="flex bg-[#F2F1F1] min-h-screen">
        {{-- Sidebar --}}
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
            {{-- Header --}}
            <div class="bg-[#187FC4] text-white rounded-2xl mb-[40px] flex items-center justify-between">
                <p class="ml-[25px] font-bold text-[25px]">Supplier Management</p>
                <div class="relative p-5 mr-[5px]">
                    <i id="profileIconBtn"
                        class="fa-solid fa-user cursor-pointer text-xl hover:opacity-80 transition-opacity relative"></i>
                    @include('components.modal_profile')
                </div>
            </div>

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{-- CARD UTAMA --}}
            <div class="bg-white rounded-2xl shadow-md p-6">
                {{-- Search and Buttons --}}
                <div class="flex items-center justify-between mb-6">
                    <form action="{{ route('supplier_management.index') }}" method="GET" class="flex items-center border border-gray-300 rounded-lg px-3 py-2 w-1/3">
                        <i class="fa-solid fa-search text-gray-400 mr-2"></i>
                        <input type="text" name="search" placeholder="Search supplier" value="{{ $search ?? '' }}"
                            class="w-full focus:outline-none text-sm text-gray-600">
                    </form>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('supplier_management.export') }}"
                            class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 text-sm font-medium">
                            <i class="fa-solid fa-file-export"></i> Export
                        </a>
                        <a href="{{ route('supplier_management.create') }}"
                            class="px-5 py-2 bg-[#187FC4] text-white rounded-lg hover:bg-[#156ca7] text-sm font-semibold cursor-pointer">
                            Add Supplier
                        </a>
                    </div>
                </div>

                {{-- Table --}}
                <div class="max-w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-[1000px] w-full text-sm text-black">
                        <thead class="bg-gray-100 text-black text-sm uppercase">
                            <tr>
                                <th class="text-left px-4 py-3">Name</th>
                                <th class="text-left px-4 py-3">Address</th>
                                <th class="text-left px-4 py-3">Telephone</th>
                                <th class="text-left px-4 py-3">Email</th>
                                <th class="text-center px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($suppliers as $supplier)
                                <tr class="bg-white hover:bg-gray-50 border-b border-gray-200 data-row">
                                    <td class="px-4 py-3">{{ $supplier->name }}</td>
                                    <td class="px-4 py-3 max-w-[300px] truncate" title="{{ $supplier->address ?? '-' }}">{{ $supplier->address ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $supplier->phone ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $supplier->email ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- View --}}
                                            <a href="{{ route('supplier_management.show', $supplier->id_supplier) }}"
                                                class="bg-[#B6FDF4] text-[#15ADA5] p-2 rounded-lg hover:bg-[#66FFEC]">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            {{-- Edit --}}
                                            <a href="{{ route('supplier_management.edit', $supplier->id_supplier) }}"
                                                class="bg-[#FFEEB7] text-[#FF8110] p-2 rounded-lg hover:bg-[#FBD65E]">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            {{-- Delete --}}
                                            <form action="{{ route('supplier_management.destroy', $supplier->id_supplier) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this supplier?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-[#FFB3BA] text-[#E20030] p-2 rounded-lg hover:bg-[#FF7C88]">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center p-4 text-gray-500">No data found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($suppliers->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $suppliers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection