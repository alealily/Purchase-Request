@extends('layouts.app')

@section('title', 'Add Purchase Request')

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

    <div class="flex-1 p-10">
        {{-- Header --}}
        <div class="bg-[#187FC4] text-white rounded-2xl mb-8 py-4 px-6">
            <h1 class="font-bold text-xl">Add New Purchase Request</h1>
        </div>

        {{-- Form Card --}}
        <div class="bg-white p-8 rounded-2xl shadow-sm">
            <form action="{{ route('purchase_request.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- Display Success/Error Messages --}}
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

                    {{-- Quotation Files (Max 3) --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold mb-2">Quotation Files (PDF, DOCX) - Max 3 files *</label>
                        <input type="file" 
                               name="quotation_files[]" 
                               id="quotationFiles"
                               accept=".pdf,.doc,.docx"
                               multiple
                               class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded-lg file:bg-gray-100 file:text-black hover:file:bg-gray-200 border border-gray-300 rounded-lg"
                               required>
                        <p class="text-xs text-gray-500 mt-1">You can select up to 3 files (PDF or Word). Max 10MB each.</p>
                        @error('quotation_files')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @error('quotation_files.*')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <div id="fileList" class="mt-2 text-sm text-gray-600"></div>
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

{{-- Max Files Warning Modal --}}
<dialog id="maxFilesModal" class="rounded-2xl shadow-xl p-0 backdrop:bg-black/50 max-w-md w-full">
    {{-- Header --}}
    <div class="flex justify-between items-center px-6 py-4">
        <h2 class="text-xl font-bold text-gray-800">Can't Add New File</h2>
        <button onclick="document.getElementById('maxFilesModal').close()" 
                class="text-gray-400 hover:text-gray-800 text-2xl leading-none">&times;</button>
    </div>
    {{-- Full-width divider --}}
    <div class="border-t-1 border-gray-300"></div>
    {{-- Body --}}
    <div class="px-6 py-5">
        <p class="text-gray-600">
            You have already selected <strong>3 files</strong> (maximum). 
            Please remove a file first before adding a new one.
        </p>
    </div>
    {{-- Footer --}}
    <div class="flex justify-end px-6 pb-6">
        <button onclick="document.getElementById('maxFilesModal').close()" 
                class="px-6 py-2 bg-[#187FC4] text-white rounded-lg font-semibold hover:bg-[#156ca7] transition">
            OK
        </button>
    </div>
</dialog>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto calculate total cost
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

        // File upload with accumulation (max 3 files)
        const fileInput = document.getElementById('quotationFiles');
        const fileList = document.getElementById('fileList');
        const MAX_FILES = 3;
        
        // Store accumulated files
        let accumulatedFiles = new DataTransfer();

        // Check before opening file dialog
        fileInput.addEventListener('click', function(e) {
            if (accumulatedFiles.files.length >= MAX_FILES) {
                e.preventDefault();
                document.getElementById('maxFilesModal').showModal();
                return false;
            }
        });

        fileInput.addEventListener('change', function() {
            const newFiles = this.files;
            
            // Add new files to accumulated list
            for (let i = 0; i < newFiles.length; i++) {
                // Check if file already exists
                let exists = false;
                for (let j = 0; j < accumulatedFiles.files.length; j++) {
                    if (accumulatedFiles.files[j].name === newFiles[i].name) {
                        exists = true;
                        break;
                    }
                }
                
                if (!exists && accumulatedFiles.files.length < MAX_FILES) {
                    accumulatedFiles.items.add(newFiles[i]);
                }
            }
            
            // Check if exceeded max
            if (accumulatedFiles.files.length > MAX_FILES) {
                alert('Maximum 3 files allowed. Some files were not added.');
                // Trim to max files
                while (accumulatedFiles.files.length > MAX_FILES) {
                    accumulatedFiles.items.remove(accumulatedFiles.files.length - 1);
                }
            }
            
            // Update the file input with accumulated files
            this.files = accumulatedFiles.files;

            // Display selected files
            updateFileList();
        });
        
        function updateFileList() {
            const files = accumulatedFiles.files;
            if (files.length > 0) {
                let html = '<strong>Selected files (' + files.length + '/' + MAX_FILES + '):</strong><ul class="list-disc ml-4 mt-1">';
                for (let i = 0; i < files.length; i++) {
                    const fileSizeMB = (files[i].size / (1024 * 1024)).toFixed(2);
                    html += `<li class="flex items-center gap-2">
                        <span>${files[i].name} (${fileSizeMB} MB)</span>
                        <button type="button" onclick="removeFile(${i})" class="text-red-500 hover:text-red-700 text-xs">
                            <i class="fa-solid fa-times"></i> Remove
                        </button>
                    </li>`;
                }
                html += '</ul>';
                fileList.innerHTML = html;
            } else {
                fileList.innerHTML = '';
            }
        }
        
        // Remove file function (make global)
        window.removeFile = function(index) {
            const newDataTransfer = new DataTransfer();
            for (let i = 0; i < accumulatedFiles.files.length; i++) {
                if (i !== index) {
                    newDataTransfer.items.add(accumulatedFiles.files[i]);
                }
            }
            accumulatedFiles = newDataTransfer;
            fileInput.files = accumulatedFiles.files;
            updateFileList();
        };
    });
</script>
@endsection
