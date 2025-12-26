@extends('layouts.app')

@section('title', 'Supplier Detail')

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

    <div class="flex-1 p-10">
        {{-- Header --}}
        <div class="bg-[#187FC4] text-white rounded-2xl mb-8 py-4 px-6">
            <h1 class="font-bold text-xl">Supplier Detail</h1>
        </div>

        {{-- Detail Card --}}
        <div class="bg-white p-8 rounded-2xl shadow-sm">
            <div class="grid grid-cols-2 gap-6">
                {{-- Name --}}
                <div class="col-span-2">
                    <label class="block text-sm text-gray-500 mb-1">Supplier Name</label>
                    <p class="font-semibold text-gray-900">{{ $supplier->name }}</p>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm text-gray-500 mb-1">Email</label>
                    <p class="font-semibold text-gray-900">{{ $supplier->email ?? '-' }}</p>
                </div>

                {{-- Telephone --}}
                <div>
                    <label class="block text-sm text-gray-500 mb-1">Telephone</label>
                    <p class="font-semibold text-gray-900">{{ $supplier->phone ?? '-' }}</p>
                </div>

                {{-- Address --}}
                <div class="col-span-2">
                    <label class="block text-sm text-gray-500 mb-1">Address</label>
                    <p class="font-semibold text-gray-900">{{ $supplier->address ?? '-' }}</p>
                </div>
            </div>

                {{-- Buttons --}}
                <div class="flex justify-start gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('supplier_management.index') }}"
                        class="px-6 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-semibold">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Back to List
                    </a>
                    <a href="{{ route('supplier_management.edit', $supplier->id_supplier) }}"
                        class="px-6 py-2 bg-[#187FC4] text-white rounded-lg hover:bg-[#156ca7] font-semibold">
                        <i class="fa-solid fa-pen-to-square mr-2"></i>Edit Supplier
                    </a>
                </div>
        </div>
    </div>
</div>
@endsection
