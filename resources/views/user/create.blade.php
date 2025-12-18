@extends('layouts.app')

@section('title', 'Add New User')

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
        {{-- Header (sama dengan Add Purchase Request) --}}
        <div class="bg-[#187FC4] text-white rounded-2xl mb-8 py-4 px-6">
            <h1 class="font-bold text-xl">Add New User</h1>
        </div>

        {{-- Form Card --}}
        <div class="bg-white p-8 rounded-2xl shadow-sm">
            <form action="{{ route('user_management.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

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
                    <div>
                        <label class="block text-sm font-semibold mb-2">Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter full name">
                    </div>

                    {{-- Badge --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">No Badge *</label>
                        <input type="text" name="badge" value="{{ old('badge') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter badge number">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter email address">
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">Role *</label>
                        <select name="role" required id="roleSelect"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-400">
                            <option value="" disabled selected hidden>Select Role</option>
                            <option value="Employee" {{ old('role') == 'Employee' ? 'selected' : '' }} class="text-black">Employee</option>
                            <option value="IT" {{ old('role') == 'IT' ? 'selected' : '' }} class="text-black">IT</option>
                            <option value="Head of Department" {{ old('role') == 'Head of Department' ? 'selected' : '' }} class="text-black">Head of Department</option>
                            <option value="Head of Division" {{ old('role') == 'Head of Division' ? 'selected' : '' }} class="text-black">Head of Division</option>
                            <option value="President Director" {{ old('role') == 'President Director' ? 'selected' : '' }} class="text-black">President Director</option>
                        </select>
                    </div>

                    {{-- Department (Text Input) --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">Department *</label>
                        <input type="text" name="department" value="{{ old('department') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter department">
                    </div>

                    {{-- Division --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">Division *</label>
                        <select name="division" id="divisionSelect" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-400">
                            <option value="" disabled selected hidden>Select Division</option>
                            <option value="General" {{ old('division') == 'General' ? 'selected' : '' }} class="text-black">General</option>
                            <option value="PCBA" {{ old('division') == 'PCBA' ? 'selected' : '' }} class="text-black">PCBA</option>
                            <option value="ASSY 1" {{ old('division') == 'ASSY 1' ? 'selected' : '' }} class="text-black">ASSY 1</option>
                            <option value="ASSY 2" {{ old('division') == 'ASSY 2' ? 'selected' : '' }} class="text-black">ASSY 2</option>
                        </select>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">Password *</label>
                        <input type="password" name="password" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter password (min 6 characters)">
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">Confirm Password *</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Confirm password">
                    </div>

                    {{-- Signature --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold mb-2">Signature *</label>
                        <input type="file" name="signature" accept="image/jpeg,image/png,image/jpg" required
                            class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded-lg file:bg-gray-100 file:text-black hover:file:bg-gray-200 border border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">Max 2MB. Supported formats: JPEG, PNG, JPG</p>
                    </div>
                </div>

                {{-- Form Actions (sama dengan Add Purchase Request) --}}
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('user_management.index') }}"
                        class="px-6 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-semibold transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-[#187FC4] text-white rounded-lg font-semibold hover:bg-[#156ca7] transition">
                        Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script to change text color when option is selected --}}
<script>
    function handleSelectColor(selectId) {
        const select = document.getElementById(selectId);
        if (select) {
            select.addEventListener('change', function() {
                if (this.value) {
                    this.classList.remove('text-gray-400');
                    this.classList.add('text-black');
                } else {
                    this.classList.remove('text-black');
                    this.classList.add('text-gray-400');
                }
            });
            // Check on load if already has value
            if (select.value) {
                select.classList.remove('text-gray-400');
                select.classList.add('text-black');
            }
        }
    }
    handleSelectColor('roleSelect');
    handleSelectColor('divisionSelect');
</script>
@endsection
