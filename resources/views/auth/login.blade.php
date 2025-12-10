<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Request Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Protest+Riot&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FDFDFD;
            /* Latar belakang abu-abu muda */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .font-protest-riot {
            font-family: 'Protest Riot', sans-serif;
        }
    </style>
</head>

<body>

    <div
        class="relative bg-white rounded-2xl shadow-2xl flex max-w-5xl w-full mx-auto my-8 overflow-hidden min-h-[600px]">

        <div class="hidden lg:flex flex-col w-1/2 p-10 bg-[#F4F5FA] relative overflow-hidden rounded-sm-2xl">
            <div class="mb-auto">
                <img src="{{ asset('storage/assets/img_logo.png') }}" alt="Siix Logo" class="w-32">
            </div>
            <div class="flex-grow flex items-center justify-center -mb-20">
                <img src="{{ asset('storage/assets/img_login.png') }}" alt="Login Illustration" class="w-full max-w-sm">
            </div>

            <img src="{{ asset('storage/assets/gel.png') }}" alt="Wave decoration"
                class="absolute bottom-0 left-0 w-56 h-auto z-0">
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-12">
            <div class="w-full max-w-md space-y-6">
                <div>
                    <h2 class="text-3xl font-bold leading-tight">AKSES SEI</h2>
                    <h1 class="text-4xl font-bold font-protest-riot leading-tight">
                        <span class="text-[#187FC4]">PURCHASE</span>
                        <span class="text-[#F39800]">REQUEST</span>
                    </h1>
                </div>

                <form id="loginForm" class="space-y-4">
                    <div> <label for="roleInput" class="block text-sm font-semibold text-gray-700">Role</label> <input
                            type="text" id="roleInput" placeholder="Choose your role..."
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
                            readonly> </div>
                    <div> <label for="usernameInput" class="block text-sm font-semibold text-gray-700">Username</label>
                        <div class="relative mt-1"> <input type="text" id="usernameInput"
                                placeholder="Enter your username"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 pr-10">
                            <span class="absolute inset-y-0 right-0 flex items-center pr-5"> <i
                                    class="fa-solid fa-user text-gray-400"></i> </span>
                        </div>
                    </div>
                    <div> <label for="passwordInput" class="block text-sm font-semibold text-gray-700">Kata
                            Sandi</label>
                        <div class="relative mt-1"> <input type="password" id="passwordInput"
                                placeholder="Enter your password"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 pr-10">
                            <span class="absolute inset-y-0 right-0 flex items-center pr-5" accesskey=""> <i
                                    class="fa-solid fa-lock text-gray-400"></i> </span>
                        </div>
                    </div>
                    <div> <button type="submit" id="loginButton"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-lg font-semibold text-white bg-[#187FC4] hover:bg-[#156ca7] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                            MASUK </button> </div>
                </form>
                <div class="text-center">
                    <p class="text-xs text-gray-500"> By clicking log in, you agree to our Terms and that you have read
                        our Privacy Policy</a>.
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Choose Role -->
    <div id="roleModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
        <div id="modalContent"
            class="bg-white rounded-2xl shadow-lg w-[90%] max-w-xl p-8 relative transform scale-95 opacity-0 transition-all duration-300">

            <!-- Header -->
            <div class="flex justify-between items-center border-b pb-3 mb-6">
                <h2 class="text-lg font-semibold text-center w-full -ml-6">Choose Role</h2>
                <button id="closeModalButton" class="absolute right-6 top-5 text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Card Container -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 place-items-center">
                <!-- Employee -->
                <div class="role-card" data-role="Employee">
                    <i class="fa-solid fa-users text-3xl mb-2"></i>
                    <p class="text-sm font-semibold">Employee</p>
                </div>

                <!-- IT -->
                <div class="role-card" data-role="IT">
                    <i class="fa-solid fa-user text-3xl mb-2"></i>
                    <p class="text-sm font-semibold">IT</p>
                </div>

                <!-- Head of Department -->
                <div class="role-card" data-role="Head of Department">
                    <i class="fa-solid fa-user-tie text-3xl mb-2"></i>
                    <p class="text-sm font-semibold text-center">Head of Department</p>
                </div>

                <!-- Head of Division -->
                <div class="role-card" data-role="Head of Division">
                    <i class="fa-solid fa-user-tie text-3xl mb-2"></i>
                    <p class="text-sm font-semibold text-center">Head of Division</p>
                </div>

                <!-- President Director -->
                <div class="role-card" data-role="President Director">
                    <i class="fa-solid fa-user-tie text-3xl mb-2"></i>
                    <p class="text-sm font-semibold text-center">President Director</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .role-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 8rem;
            /* ukuran seragam */
            height: 8rem;
            background-color: #fff;
            border: 2px solid #e5e7eb;
            /* abu muda (#e5e7eb = gray-200) */
            border-radius: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .role-card:hover {
            border-color: #187FC4;
            /* biru utama */
            box-shadow: 0 4px 10px rgba(24, 127, 196, 0.15);
            transform: translateY(-2px);
        }

        .role-card i {
            color: #374151;
            /* abu tua */
        }

        .role-card p {
            color: #111827;
            /* teks hitam */
        }

        .role-card:hover i,
        .role-card:hover p {
            color: #187FC4;
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleInput = document.getElementById('roleInput');
            const roleModal = document.getElementById('roleModal');
            const modalContent = document.getElementById('modalContent');
            const closeModalButton = document.getElementById('closeModalButton');
            const roleCards = document.querySelectorAll('.role-card');
            const loginForm = document.getElementById('loginForm');

            function openModal() {
                roleModal.classList.remove('hidden');
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function closeModal() {
                modalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    roleModal.classList.add('hidden');
                    modalContent.classList.remove('scale-100', 'opacity-100');
                }, 300);
            }

            roleInput.addEventListener('click', openModal);
            closeModalButton.addEventListener('click', closeModal);
            roleModal.addEventListener('click', (e) => {
                if (e.target === roleModal) closeModal();
            });

            roleCards.forEach(card => {
                card.addEventListener('click', function () {
                    roleInput.value = this.dataset.role;
                    closeModal();
                });
            });

            loginForm.addEventListener('submit', function (e) {
                e.preventDefault(); // cegah reload form

                const username = document.getElementById('usernameInput').value.trim();
                const password = document.getElementById('passwordInput').value.trim();
                const role = document.getElementById('roleInput').value.trim();

                if (!username || !password || !role) {
                    alert('Silakan isi semua kolom dan pilih role terlebih dahulu.');
                    return;
                }

                // Redirect ke halaman dashboard (ganti sesuai route Laravel kamu)
                window.location.href = "/dashboard";
            });
        });

    </script>

</body>