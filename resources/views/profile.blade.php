<x-main title="Profil" class="p-0 min-h-screen flex flex-col">
    <x-home.navbar />

    <div class="flex-1 p-6 bg-base-200">
        <div class="max-w-7xl mx-auto flex flex-col gap-6">
            {{-- Ringkasan --}}
            <div class="grid sm:grid-cols-2 md:grid-cols-5 gap-4">
                @php
                    $mindmapCount = Auth::user()->mindmaps->count();
                    $progress = min(100, round(($mindmapCount / 100) * 100));
                @endphp

                <x-profile.stat icon="user" label="Nama" value="{{ Auth::user()->name }}" color="primary" />

                <x-profile.stat icon="mail" label="Email" value="{{ Auth::user()->email }}" color="secondary" />

                <x-profile.stat icon="shield-check" label="Peran" value="{{ Auth::user()->role }}" color="success" />

                <x-profile.stat icon="folder" label="Mindmap" value="{{ Auth::user()->mindmaps->count() }}"
                    color="warning" />

                <x-profile.stat icon="graduation-cap" label="Tipe Akun" value="{{ Auth::user()->account_type }}"
                    color="danger" />


            </div>

            {{-- QR & Login Info --}}
            <div class="grid md:grid-cols-2 gap-6">
                {{-- QR Kode --}}
                <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center justify-center">
                    <h2 class="font-semibold text-lg mb-4 flex items-center gap-2">
                        <x-lucide-qr-code class="w-5 h-5" /> QR Kode Profil
                    </h2>
                    <div>
                        {!! $qrCode !!}
                    </div>
                    <p class="mt-3 text-sm text-gray-500 text-center">Pindai untuk melihat identitas profil Anda</p>
                </div>

                {{-- Info Login --}}
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="font-semibold text-lg mb-4 border-b pb-2 flex items-center gap-2">
                        <x-lucide-clock class="w-5 h-5" /> Aktivitas Login Terakhir
                    </h2>
                    @if ($logins->isEmpty())
                        <p class="text-sm text-gray-500 italic">Belum ada riwayat login yang tercatat.</p>
                    @else
                        <ul class="text-sm text-gray-700 space-y-2">
                            @foreach ($logins as $log)
                                <li>
                                    <div class="flex justify-between items-center">
                                        <span>{{ \Carbon\Carbon::parse($log->logged_in_at)->format('d M Y H:i') }}</span>
                                        <span class="text-xs text-gray-400">{{ $log->ip_address }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            {{-- Form Profil + Password --}}
            <div class="flex flex-col lg:flex-row gap-6">
                {{-- Form Profil --}}
                <div class="bg-white shadow rounded-lg p-6 w-full lg:w-1/2">
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Akun</h2>
                    <form method="POST" action="{{ route('update_profile_name', Auth::id()) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-control mb-4">
                            <label class="label font-semibold">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                                class="input input-bordered w-full text-black" required>
                            @error('name')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label font-semibold">Email</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                class="input input-bordered w-full text-black" required>
                            @error('email')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-full mt-2">Perbarui Informasi</button>
                    </form>
                </div>

                {{-- Ganti Password --}}
                <div class="bg-white shadow rounded-lg p-6 w-full lg:w-1/2">
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">Ganti Password</h2>
                    <form method="POST" action="{{ route('update_password') }}">
                        @csrf
                        @method('PUT')

                        @foreach ([['label' => 'Password Lama', 'name' => 'password_lama'], ['label' => 'Password Baru', 'name' => 'password_baru'], ['label' => 'Konfirmasi Password Baru', 'name' => 'konfirmasi_password_baru']] as $field)
                            <div class="form-control mb-4">
                                <label class="label font-semibold">{{ $field['label'] }}:</label>
                                <input type="password" name="{{ $field['name'] }}"
                                    class="input input-bordered w-full text-black" required>
                                @error($field['name'])
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-secondary w-full mt-2">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-main>
