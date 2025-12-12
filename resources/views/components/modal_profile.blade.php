{{-- Profile Popup - Dropdown style at top right --}}
@php
    $user = auth()->user();
    $userRole = strtolower($user->role ?? '');
    
    // Role badge colors (same as User Management)
    // Using match with lowercase comparison for flexibility
    $roleBadgeClass = match(true) {
        $userRole === 'employee' => 'bg-[#15ADA5] text-white',
        $userRole === 'it' => 'bg-[#0A7D0C] text-white',
        str_contains($userRole, 'head of department') || $userRole === 'head of department' => 'bg-[#FF8110] text-white',
        str_contains($userRole, 'head of division') || $userRole === 'head of division' => 'bg-[#155D97] text-white',
        str_contains($userRole, 'president') || $userRole === 'president director' => 'bg-[#F10000] text-white',
        $userRole === 'superior' => 'bg-[#FF8110] text-white', // fallback for 'superior' role
        default => 'bg-gray-200 text-gray-800',
    };
    
    // Role display name - show original role name from database
    $roleDisplayName = match(true) {
        $userRole === 'employee' => 'Employee',
        $userRole === 'it' => 'IT',
        $userRole === 'head of department' => 'Head of Department',
        $userRole === 'head of division' => 'Head of Division',
        $userRole === 'president director' => 'President Director',
        $userRole === 'superior' => 'Superior',
        default => ucwords($user->role ?? 'User'),
    };
@endphp

<div id="profilePopup" class="hidden absolute top-[calc(100%_+_10px)] right-0 
    bg-white rounded-2xl shadow-xl w-80 z-50 border border-gray-200 overflow-hidden">
    
    <div class="p-6">
        {{-- User Info Section --}}
        <div class="flex items-center gap-4">
            {{-- Avatar --}}
            <div class="flex-shrink-0">
                <div class="w-16 h-16 bg-white rounded-full border-2 border-gray-300 
                        flex items-center justify-center">
                    <i class="fa-solid fa-user text-gray-400 text-2xl"></i>
                </div>
            </div>
            
            {{-- Name & Email --}}
            <div class="flex-1 min-w-0">
                <p class="text-base font-bold text-gray-900 truncate">
                    {{ $user->name ?? 'User Name' }}
                </p>
                <p class="text-sm text-gray-500 truncate">
                    {{ $user->email ?? 'user@email.com' }}
                </p>
            </div>
        </div>
        
        {{-- Role Badge --}}
        <div class="mt-4">
            <span class="inline-block px-4 py-1.5 {{ $roleBadgeClass }} rounded-full text-sm font-semibold">
                {{ $roleDisplayName }}
            </span>
        </div>
        
        {{-- Logout Button --}}
        <div class="mt-5">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="block w-full text-center bg-[#187FC4] text-white 
                        font-bold py-3 rounded-xl hover:bg-[#156ca7] transition-colors cursor-pointer">
                    LOGOUT
                </button>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript for toggle popup - using unique function to avoid conflicts --}}
<script>
(function() {
    // Use IIFE to avoid global scope pollution
    function initProfilePopup() {
        const profileIconBtn = document.getElementById('profileIconBtn');
        const profilePopup = document.getElementById('profilePopup');
        
        if (!profileIconBtn || !profilePopup) {
            console.warn('Profile popup elements not found');
            return;
        }
        
        // Remove any existing listeners by cloning (prevents duplicate listeners)
        const newProfileIconBtn = profileIconBtn.cloneNode(true);
        profileIconBtn.parentNode.replaceChild(newProfileIconBtn, profileIconBtn);
        
        // Toggle popup on icon click
        newProfileIconBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            event.preventDefault();
            profilePopup.classList.toggle('hidden');
        });
        
        // Close popup when clicking outside
        document.addEventListener('click', function(event) {
            if (!profilePopup.contains(event.target) && event.target !== newProfileIconBtn && !newProfileIconBtn.contains(event.target)) {
                profilePopup.classList.add('hidden');
            }
        });
    }
    
    // Run on DOMContentLoaded or immediately if already loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProfilePopup);
    } else {
        initProfilePopup();
    }
})();
</script>