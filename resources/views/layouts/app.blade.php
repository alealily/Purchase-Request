<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('storage/assets/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <link href="https://fonts.googleapis.com/css2?family=Protest+Riot&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Purchase Request</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="font-poppins overflow-x-hidden">
    {{-- Halaman konten --}}
    @yield('content')

    {{-- Include modal profile --}}
    @include('components.modal_profile')

    {{-- Script untuk toggle modal --}}
    <script>
        const profileIcon = document.querySelector("#profileIcon");
        const profileModal = document.querySelector("#profileModal");

        if (profileIcon) {
            profileIcon.addEventListener("click", () => {
                profileModal.classList.remove("hidden");
            });
        }

        // Klik luar modal = close
        profileModal?.addEventListener("click", (e) => {
            if (e.target === profileModal) {
                profileModal.classList.add("hidden");
            }
        });
    </script>
</body>
</html>
