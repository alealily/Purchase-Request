@extends('layouts.app')

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
                <p class="ml-[25px] font-bold text-[25px]">User Detail</p>
                <div class="relative p-5 mr-[5px]">
                    <i id="profileIconBtn" class="fa-solid fa-user cursor-pointer text-xl hover:opacity-80"></i>
                    @include('components.modal_profile')
                </div>
            </div>

            {{-- Detail Card --}}
            <div class="bg-white rounded-2xl shadow-md p-8">
                {{-- User Info Grid --}}
                <div class="grid grid-cols-2 gap-6">
                    {{-- Name --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Name</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $user->name }}</p>
                    </div>

                    {{-- Badge --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">No Badge</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $user->badge }}</p>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Email</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $user->email }}</p>
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Role</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $user->role }}</p>
                    </div>

                    {{-- Position --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Position</label>
                        <p class="text-lg font-semibold text-gray-800">{{ ucwords(str_replace('_', ' ', $user->position ?? '-')) }}</p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Status</label>
                        @if($user->is_active)
                            <span class="px-3 py-1 bg-[#1ECB57] text-white rounded-lg text-sm font-semibold">Active</span>
                        @else
                            <span class="px-3 py-1 bg-[#6E6D6D] text-white rounded-lg text-sm font-semibold">Inactive</span>
                        @endif
                    </div>

                    {{-- Department --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Department</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $user->department ?? '-' }}</p>
                    </div>

                    {{-- Division --}}
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Division</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $user->division ?? '-' }}</p>
                    </div>

                    {{-- Signature --}}
                    @if($user->signature)
                        <div class="col-span-2">
                            <label class="block text-sm text-gray-500 mb-2">Signature</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 inline-block">
                                <img src="{{ asset('storage/' . $user->signature) }}" alt="User Signature" 
                                    class="max-h-32">
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Buttons --}}
                <div class="flex justify-start gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('user_management.index') }}"
                        class="px-6 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-semibold">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Back to List
                    </a>
                    <a href="{{ route('user_management.edit', $user->id_user) }}"
                        class="px-6 py-2 bg-[#187FC4] text-white rounded-lg hover:bg-[#156ca7] font-semibold">
                        <i class="fa-solid fa-pen-to-square mr-2"></i>Edit User
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
