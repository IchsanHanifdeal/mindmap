<x-main title="{{ $title }}" class="!p-0" full>

    <div
        class="block lg:hidden min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 px-6">
        <div class="text-center max-w-md">
            <div class="mb-6 flex justify-center text-gray-800">
                <x-lucide-ban class="w-20 h-20 stroke-2" />
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-2">Fitur Tidak Tersedia di Mobile</h1>
            <p class="text-gray-600 mb-6">
                Silakan akses melalui perangkat dengan layar lebih besar<br>(Laptop atau Desktop) untuk pengalaman
                terbaik.
            </p>

            <a href="{{ route('beranda') }}"
                class="inline-flex items-center justify-center bg-[#123f77] hover:bg-[#0f86b6] text-white font-semibold px-6 py-3 rounded-xl shadow transition">
                <x-lucide-arrow-left class="w-5 h-5 mr-2" />
                Kembali ke Beranda
            </a>
        </div>
    </div>

    <div class="hidden lg:block">
        <div class="drawer lg:drawer-open">
            <input id="aside-dashboard" type="checkbox" class="drawer-toggle" />

            <div class="drawer-content flex flex-col">
                @include('components.home.navbar')

                <div class="p-4 md:p-6 flex-1">
                    <div class="flex flex-col gap-6">
                        {{ $slot }}
                    </div>
                </div>

                @include('components.footer')
            </div>

            @include('components.home.sidebar')
        </div>
    </div>

</x-main>
