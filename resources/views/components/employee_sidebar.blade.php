<div class="w-64 min-h-screen bg-white flex flex-col">
    <!-- Logo -->
    <div class="flex items-center justify-center py-6">
        <img src="{{ asset('/storage/assets/img_logo.png') }}" alt="Logo" class="img-logo">
    </div>

    <!-- Divider -->
    <div class="border-b border-[#187FC4] border-[8px]"></div>

    <!-- Menu -->
    <nav class="flex-1 px-4 py-6 space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('dashboard.index') }}"
           class="flex items-center gap-3 py-3 px-4 rounded-lg font-semibold
           {{ request()->routeIs('dashboard.index') ? 'bg-[#187FC4] text-white' : 'text-black hover:bg-gray-100' }}">
            <i class="fi fi-sr-apps"></i>
            Dashboard
        </a>

        <!-- Add Purchase Request -->
        <a href="{{ route('purchase_request.index') }}"
           class="flex items-center gap-3 py-3 px-4 rounded-lg font-semibold
           {{ request()->routeIs('purchase_request.*') ? 'bg-[#187FC4] text-white' : 'text-black hover:bg-gray-100' }}">
            <i class="fa-solid fa-file-circle-plus"></i>
            Add Purchase Request
        </a>

        <!-- Purchase Request Detail -->
        <a href="{{ route('pr_detail.index') }}"
           class="flex items-center gap-3 py-3 px-4 rounded-lg font-semibold
           {{ request()->routeIs('pr_detail.index') ? 'bg-[#187FC4] text-white' : 'text-black hover:bg-gray-100' }}">
            <i class="fa-solid fa-file-lines"></i>
            Purchase Request Detail
        </a>
    </nav>
</div>
