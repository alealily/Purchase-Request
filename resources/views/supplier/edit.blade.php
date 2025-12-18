@extends('layouts.app')

@section('title', 'Edit Supplier')

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
            <h1 class="font-bold text-xl">Edit Supplier</h1>
        </div>

        {{-- Form Card --}}
        <div class="bg-white p-8 rounded-2xl shadow-sm">
            <form action="{{ route('supplier_management.update', $supplier->id_supplier) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <strong>Please fix the following errors:</strong>
                        <ul class="list-disc ml-4 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-6">
                    {{-- Name --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold mb-2">Supplier Name *</label>
                        <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter supplier name">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $supplier->email) }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter email address">
                    </div>

                    {{-- Telephone --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">Telephone *</label>
                        <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter telephone number">
                    </div>

                    {{-- Address --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold mb-2">Address *</label>
                        <textarea name="address" rows="3" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter supplier address">{{ old('address', $supplier->address) }}</textarea>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('supplier_management.index') }}"
                        class="px-6 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-semibold transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-[#187FC4] text-white rounded-lg font-semibold hover:bg-[#156ca7] transition">
                        Update Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
