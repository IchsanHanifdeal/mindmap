<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="emerald">

<head>
    @include('components.head')
    <style>
        .toast {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .toast-show {
            opacity: 1;
        }

        body {
            background-color: #F1F1F1;
            color: #23120B;
        }

        .toast {
            background-color: #FFFFFF;
            border-left-color: #FDB827;
            color: #23120B;
        }

        .toast button {
            color: #23120B;
        }

        @keyframes spin-reverse {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(-360deg);
            }
        }

        .reverse-spin {
            animation: spin-reverse 1s linear infinite;
        }
    </style>
</head>

<body class="flex flex-col mx-auto bg-[#F1F1F1] font-sans">
    <main class="{{ $class ?? 'p-4' }}" role="main">
        <div id="splash-screen"
            class="fixed inset-0 flex items-center justify-center min-h-screen z-[9999] transition-opacity duration-500 ease-in-out opacity-100 bg-[#c8d69b]">
            <div
                class="relative flex flex-col items-center justify-center p-8 rounded-3xl shadow-lg bg-white bg-opacity-90 backdrop-blur-lg border border-gray-200 transition-transform duration-1000 ease-in-out animate-pulse scale-100 hover:scale-105">

                <!-- Loading Animation -->
                <div class="relative w-24 h-24 mb-6">
                    <div
                        class="absolute inset-0 rounded-full border-8 border-t-[#123f77] border-r-transparent border-b-[#123f77] border-l-transparent animate-spin">
                    </div>
                    <div
                        class="absolute inset-2 rounded-full border-8 border-t-[#0f86b6] border-r-transparent border-b-[#0f86b6] border-l-transparent animate-spin reverse-spin">
                    </div>
                </div>

                <!-- Branding -->
                <h1 class="text-4xl font-bold text-[#123f77] tracking-wide">Mindmapku</h1>
                <p class="text-sm mt-2 italic text-gray-600">Tunggu sebentar, sedang memuat...</p>
            </div>
        </div>

        {{ $slot }}

        <div id="toast-container" class="fixed z-50 space-y-4 top-5 right-5"></div>

        <script>
            function showImageModal(imageUrl) {
                Swal.fire({
                    imageUrl: imageUrl,
                    imageAlt: 'Gambar besar',
                    showCloseButton: true,
                    showConfirmButton: false,
                    background: '#fff',
                    width: '80%',
                    padding: '1em',
                    customClass: {
                        image: 'rounded shadow'
                    }
                });
            }

            function showLoginAlert() {
                Swal.fire({
                    icon: 'error',
                    title: 'Anda Belum Login',
                    html: `
                <p class="text-gray-600 mb-2">
                    Untuk mengakses fitur ini, silakan login terlebih dahulu.
                </p>
            `,
                    footer: '<span class="text-sm text-gray-500">Akses hanya tersedia untuk pengguna terdaftar</span>',
                    confirmButtonText: 'Login Sekarang',
                    confirmButtonColor: '#123f77',
                    cancelButtonColor: '#d33',
                    background: '#fff',
                    customClass: {
                        popup: 'rounded-xl',
                        confirmButton: 'px-4 py-2 text-base',
                        cancelButton: 'px-4 py-2 text-base'
                    },
                    buttonsStyling: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('login') }}";
                    }
                });
            }
        </script>

        <script>
            function showMindmapTypeSelector() {
                Swal.fire({
                    title: 'Pilih Jenis Mindmap',
                    input: 'select',
                    inputOptions: {
                        spider: 'Spider Map',
                        flow: 'Flow Map',
                        multi: 'Multi-flow Map',
                        bubble: 'Bubble Map',
                        brace: 'Brace Map',
                        custom: 'Custom Map'
                    },
                    inputPlaceholder: 'Pilih satu jenis',
                    showCancelButton: true,
                    confirmButtonText: 'Lanjutkan',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        const selectedType = result.value;
                        window.location.href = `/mindmap/${selectedType}`;
                    }
                });
            }

            document.getElementById('startMindmapBtn')?.addEventListener('click', showMindmapTypeSelector);
            document.getElementById('startMindmapBtnNavbar')?.addEventListener('click', showMindmapTypeSelector);
        </script>

        <script>
            function closeAllModals(event) {
                const form = event.target.closest('form');

                if (form) {
                    form.submit();

                    const modals = document.querySelectorAll('dialog.modal');

                    modals.forEach(modal => {
                        if (modal.hasAttribute('open')) {
                            modal.close();
                        }
                    });
                }
            }
        </script>

        <script>
            function showToast(message, type) {
                const toastContainer = document.getElementById('toast-container');
                const toast = document.createElement('div');

                toast.classList.add(
                    'relative', 'shadow-lg', 'p-4', 'rounded-lg', 'flex',
                    'items-center', 'justify-between', 'border-l-4', `border-${type}`,
                    'transition-transform', 'transition-opacity', 'transform', 'duration-300', 'ease-in-out',
                    'opacity-0', 'translate-x-full', 'bg-[#364C84]', 'text-white'
                );

                toast.innerHTML = `
                <div class="flex items-center flex-grow space-x-2">
                    <span class="font-semibold">${message}</span>
                </div>
                <button class="ml-4 btn btn-sm btn-circle btn-ghost" onclick="this.parentElement.remove()">✕</button>
            `;

                toastContainer.appendChild(toast);

                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                    toast.classList.add('translate-x-0', 'opacity-100');
                }, 100);

                setTimeout(() => {
                    toast.classList.remove('translate-x-0', 'opacity-100');
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 15000);
            }

            @if (session('toast'))
                showToast('{{ session('toast.message') }}', '{{ session('toast.type') }}');
            @endif
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var splashScreen = document.getElementById('splash-screen');

                splashScreen.classList.add('show');

                window.addEventListener('load', function() {
                    splashScreen.classList.remove('show');
                });
            });

            window.addEventListener('beforeunload', function() {
                var splashScreen = document.getElementById('splash-screen');
                splashScreen.classList.add('show');
            });
        </script>

    </main>
    @stack('scripts')
</body>

</html>
