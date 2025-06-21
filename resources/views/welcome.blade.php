<x-main class="p-0">
    <x-home.navbar />

    <section class="bg-gradient-to-br from-[#f0f6ff] to-[#e0ebff] py-24 px-6 text-center">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-extrabold text-[#123f77] leading-tight">
                Digital Mind Mapping untuk Pembaca Kritis
            </h1>
            <p class="mt-5 text-lg md:text-xl text-gray-700">
                Visualisasikan ide, rangkum bacaan, dan refleksikan pemahamanmu dalam satu platform edukatif yang interaktif.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('mindmap') }}" class="btn bg-[#123f77] hover:bg-[#0f86b6] text-white font-semibold px-6 py-3 rounded-xl">
                    Mulai Mindmap Baru
                </a>
                <a href="#fitur" class="btn border border-[#123f77] text-[#123f77] hover:bg-[#eef4ff] font-semibold px-6 py-3 rounded-xl">
                    Lihat Fitur
                </a>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-20 px-6 bg-white">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-[#123f77] mb-12">
                Fitur Unggulan
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-left">
                <div class="p-6 bg-[#f9fafb] rounded-xl shadow-md hover:shadow-lg transition duration-300">
                    <div class="mb-4">
                        <div class="w-12 h-12 bg-[#e0f2fe] text-[#0f86b6] flex items-center justify-center rounded-full">
                            🧠
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-[#0f86b6] mb-2">Mind Mapping Interaktif</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Rancang peta pikiran dengan node dinamis, warna, ikon, dan struktur hierarkis yang fleksibel.
                    </p>
                </div>
                <div class="p-6 bg-[#f9fafb] rounded-xl shadow-md hover:shadow-lg transition duration-300">
                    <div class="mb-4">
                        <div class="w-12 h-12 bg-[#e0f2fe] text-[#0f86b6] flex items-center justify-center rounded-full">
                            📄
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-[#0f86b6] mb-2">Ringkasan Otomatis</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Dapatkan ringkasan otomatis dari bacaan dan struktur peta untuk pemahaman cepat dan efektif.
                    </p>
                </div>
                <div class="p-6 bg-[#f9fafb] rounded-xl shadow-md hover:shadow-lg transition duration-300">
                    <div class="mb-4">
                        <div class="w-12 h-12 bg-[#e0f2fe] text-[#0f86b6] flex items-center justify-center rounded-full">
                            🤝
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-[#0f86b6] mb-2">Kolaborasi Siswa</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kolaborasi antar siswa dalam satu peta pikiran untuk berbagi ide dan membangun pemahaman bersama.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-[#f5fbe4]">
        <div class="text-center max-w-3xl mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-[#123f77] mb-4">
                Siap Menjadi Pembaca Kritis?
            </h2>
            <p class="text-gray-700 text-lg mb-8">
                Bangun peta pikiranmu, tulis ringkasan, dan refleksikan pemahamanmu dengan cara yang lebih cerdas.
            </p>
            <a href="{{ route('register') }}" class="btn bg-[#123f77] text-white hover:bg-[#0f86b6] px-6 py-3 rounded-xl font-semibold">
                Daftar Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-6 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} Mindmapku. Semua hak dilindungi.
    </footer>
</x-main>
