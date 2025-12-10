@extends('layouts.app')

@section('title', 'Add Purchase Request')

@section('content')
<div class="flex bg-[#F4F5FA] min-h-screen">
    {{-- Sidebar --}}
    <aside class="w-64 bg-white h-screen sticky top-0">
        @include('components.it_sidebar')
    </aside>

    <div class="flex-1 p-10">
        {{-- Header --}}
        <div class="bg-[#187FC4] text-white rounded-2xl mb-8 p-6">
            <h1 class="font-bold text-2xl">Add New Purchase Request</h1>
        </div>

        {{-- Form Card --}}
        <div class="bg-white p-8 rounded-2xl shadow-sm">
            <form action="{{ route('purchase_request.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-2 gap-6">
                    {{-- Material Description --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold mb-2">Material Description *</label>
                        <input type="text" 
                               name="material_desc" 
                               value="{{ old('material_desc') }}"
                               class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Enter material description"
                               required>
                        @error('material_desc')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- UOM --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">UOM</label>
                        <input type="text" 
                               value="PCS" 
                               disabled
                               class="w-full border border-gray-300 px-4 py-2 rounded-lg bg-gray-100 text-gray-500">
                    </div>

                    {{-- Currency --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">Currency</label>
                        <input type="text" 
                               value="RP" 
                               disabled
                               class="w-full border border-gray-300 px-4 py-2 rounded-lg bg-gray-100 text-gray-500">
                    </div>

                    {{-- Unit Price --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">Unit Price *</label>
                        <input type="number" 
                               name="unit_price" 
                               value="{{ old('unit_price') }}"
                               class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0"
                               min="0"
                               required>
                        @error('unit_price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Quantity --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">Quantity *</label>
                        <input type="number" 
                               name="quantity" 
                               value="{{ old('quantity') }}"
                               class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0"
                               min="1"
                               required>
                        @error('quantity')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Total Cost (Auto Calculate) --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">Total Cost</label>
                        <input type="text" 
                               id="totalCost"
                               readonly
                               class="w-full border border-gray-300 px-4 py-2 rounded-lg bg-gray-100 font-bold"
                               value="0">
                    </div>

                    {{-- Supplier --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">Supplier *</label>
                        <select name="id_supplier" 
                                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="">-- Select Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id_supplier }}" {{ old('id_supplier') == $supplier->id_supplier ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_supplier')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Quotation File --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">Quotation File (PDF, DOCX) *</label>
                        <input type="file" 
                               name="quotation_file" 
                               accept=".pdf,.docx"
                               class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded-lg file:bg-gray-100 file:text-black hover:file:bg-gray-200 border border-gray-300 rounded-lg"
                               required>
                        @error('quotation_file')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('purchase_request.index') }}" 
                       class="px-6 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-semibold transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-[#187FC4] text-white rounded-lg font-semibold hover:bg-[#156ca7] transition">
                        Create Purchase Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto calculate total cost
    document.addEventListener('DOMContentLoaded', function() {
        const unitPriceInput = document.querySelector('input[name="unit_price"]');
        const quantityInput = document.querySelector('input[name="quantity"]');
        const totalCostInput = document.getElementById('totalCost');

        function calculateTotal() {
            const price = parseFloat(unitPriceInput.value) || 0;
            const qty = parseInt(quantityInput.value) || 0;
            const total = price * qty;
            totalCostInput.value = total.toLocaleString('id-ID');
        }

        unitPriceInput.addEventListener('input', calculateTotal);
        quantityInput.addEventListener('input', calculateTotal);
    });
</script>
@endsection
