<x-main title="Daftar Mindmap" class="p-0" full>
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
        <!-- Left Side with Image -->
        <div class="hidden lg:flex w-1/2 relative items-center justify-center overflow-hidden">
            <img src="{{ asset('img/background.jpg') }}" alt="Background"
                class="absolute inset-0 w-full h-full object-cover z-0" />
            <div class="absolute inset-0 bg-black/60 z-0"></div>
            <div class="z-10 px-16 text-center animate-fade-in text-white">
                <h2 class="text-4xl font-extrabold leading-relaxed">
                    Gabung dengan <span class="gradient-text">Digital Mind Mapping OPIRSURE</span>
                </h2>
                <p class="mt-4 text-lg text-white/90 max-w-lg mx-auto">
                    Ciptakan peta pikiranmu dan kembangkan pemahaman kritis secara visual.
                </p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="lg:w-1/2 w-full flex items-center justify-center relative px-6 lg:px-16 py-12 bg-base-100">
            <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8 z-10 animate-fade-in">
                <h1 class="text-center mb-3 leading-tight">
                    <span class="block text-3xl md:text-4xl font-extrabold text-[#0d3970] drop-shadow-sm">
                        Daftar ke Platform
                    </span>
                    <span class="block text-xl md:text-2xl font-bold tracking-wide text-[#e8891a] uppercase">
                        Digital Mind Mapping OPIRSURE
                    </span>
                </h1>

                <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="label text-sm text-primary">Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Nama Lengkap"
                            class="input input-bordered w-full {{ $errors->has('name') ? 'input-error' : '' }}"
                            value="{{ old('name') }}">
                        @error('name')
                            <small class="text-error">{{ $message }}</small>
                        @enderror
                    </div>

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
                        <label class="label text-sm text-primary">Jenis Akun</label>
                        <select name="account_type" required
                            class="select select-bordered w-full {{ $errors->has('account_type') ? 'select-error' : '' }}">
                            <option value="" disabled selected>Pilih jenis akun</option>
                            <option value="mahasiswa" {{ old('account_type') == 'mahasiswa' ? 'selected' : '' }}>
                                Mahasiswa
                            </option>
                            <option value="dosen" {{ old('account_type') == 'dosen' ? 'selected' : '' }}>
                                Dosen
                            </option>
                            <option value="tutor" {{ old('account_type') == 'tutor' ? 'selected' : '' }}>
                                Tutor
                            </option>
                            <option value="personal" {{ old('account_type') == 'personal' ? 'selected' : '' }}>
                                Personal
                            </option>
                        </select>
                        @error('account_type')
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

                    <div>
                        <label class="label text-sm text-primary">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi Password"
                            class="input input-bordered w-full">
                    </div>

                    <button type="submit"
                        class="btn bg-primary text-white w-full hover:bg-[#0f86b6] hover:scale-[1.01] transition-all">
                        Daftar
                    </button>
                </form>

                <div class="mt-4 text-center space-y-2">
                    <p class="text-sm text-gray-500">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-primary font-medium hover:text-[#0f86b6]">
                            Masuk Sekarang</a>
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
