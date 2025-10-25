<x-main title="Login Mindmap" class="p-0" full>
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.8s ease-out;
        }

        body {
            background-color: #f1f5fb;
        }

        .gradient-text {
            background: linear-gradient(90deg, #123f77, #0f86b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-primary {
            color: #123f77 !important;
        }

        .bg-primary {
            background-color: #123f77 !important;
        }

        .bg-secondary {
            background-color: #eef4ff;
        }
    </style>

    <section class="min-h-screen flex items-stretch bg-secondary">
        <div class="hidden lg:flex w-1/2 relative items-center justify-center overflow-hidden">
            <!-- Fullscreen Background Image -->
            <img src="{{ asset('img/background.jpg') }}" alt="Background"
                class="absolute inset-0 w-full h-full object-cover z-0" />

            <!-- Overlay Gelap agar teks tetap terbaca -->
            <div class="absolute inset-0 bg-black/60 z-0"></div>

            <!-- Konten Teks -->
            <div class="z-10 px-10 md:px-16 text-center animate-fade-in text-white">
                <h2 class="text-4xl md:text-5xl font-extrabold leading-tight drop-shadow-md">
                    Welcome to
                    <span class="gradient-text">Digital Mind Mapping OPIRSURE</span>
                </h2>

                <p class="mt-5 text-lg md:text-xl text-white/90 max-w-2xl mx-auto font-light tracking-wide">
                    Tempat terbaik untuk membangun pemahaman kritis melalui peta pikiran digital yang interaktif.
                </p>

                <div class="mt-6 flex justify-center">
                    <div class="h-1 w-20 bg-white/30 rounded-full"></div>
                </div>
            </div>

        </div>

        <!-- Right Side / Form -->
        <div class="lg:w-1/2 w-full flex items-center justify-center relative px-6 lg:px-16 py-12 bg-base-100">
            <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8 z-10 animate-fade-in">
                <h1 class="text-center mb-3 leading-tight">
                    <span class="block text-3xl md:text-4xl font-extrabold text-[#0d3970] drop-shadow-sm">
                        Masuk ke Platform
                    </span>
                    <span class="block text-xl md:text-2xl font-bold tracking-wide text-[#e8891a] uppercase">
                        Digital Mind Mapping OPIRSURE
                    </span>
                </h1>
                <p class="text-center text-gray-500 text-sm mb-6">
                    Visualisasikan pemahamanmu, mulai sekarang.
                </p>

                <form method="POST" action="{{ route('auth') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="label text-sm text-primary">Email</label>
                        <input type="email" name="email" required placeholder="Email"
                            class="input input-bordered w-full {{ $errors->has('email') ? 'input-error' : '' }}"
                            value="{{ old('email') }}">
                        @error('email')
                            <small class="text-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div>
                        <label class="label text-sm text-primary">Password</label>
                        <input type="password" name="password" required placeholder="Password"
                            class="input input-bordered w-full {{ $errors->has('password') ? 'input-error' : '' }}">
                        @error('password')
                            <small class="text-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit"
                        class="btn bg-primary text-white w-full hover:bg-[#0f86b6] hover:scale-[1.01] transition-all">
                        Masuk
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-500">Belum punya akun?
                        <a href="{{ route('register') }}" class="text-primary font-medium hover:text-[#0f86b6]">Daftar
                            Sekarang</a>
                    </p>
                    <a href="{{ route('beranda') }}"
                        class="inline-block text-sm text-gray-500 hover:text-primary transition">
                        ← Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-main>
