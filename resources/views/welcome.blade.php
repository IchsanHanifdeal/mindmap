<x-main class="p-0 min-h-screen flex flex-col">
    <x-home.navbar />

    <section
        class="relative flex-grow flex items-center justify-center bg-gradient-to-br from-[#f4f8ff] via-[#e8f0ff] to-[#d7e6ff] px-6 overflow-hidden">

        <!-- Decorative Background Shapes -->
        <div class="absolute top-0 right-0 w-40 h-40 bg-[#0f86b6]/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-52 h-52 bg-[#123f77]/20 rounded-full blur-3xl"></div>

        <div class="relative max-w-4xl mx-auto text-center">
            <!-- Branding -->
            <div class="flex flex-col mb-6 leading-tight">
                <span class="text-[#0d3970] font-extrabold text-4xl md:text-5xl drop-shadow-sm">
                    Digital Mind Mapping
                </span>
                <span class="text-[#e8891a] font-bold tracking-[0.15em] text-lg md:text-xl uppercase">
                    Opirsure
                </span>
            </div>

            <p class="mt-2 text-lg md:text-xl text-gray-700 max-w-2xl mx-auto">
                Visualisasikan ide, rangkum bacaan, dan refleksikan pemahamanmu dalam satu platform edukatif yang interaktif.
            </p>

            <div class="mt-10 flex flex-wrap justify-center gap-4">
                @auth
                    <a href="{{ route('materi') }}"
                        class="px-8 py-3 rounded-xl bg-[#123f77] text-white hover:bg-[#0f86b6] font-semibold shadow-lg transition-transform hover:scale-105">
                        Mulai Jelajah
                    </a>
                @else
                    <button onclick="showLoginAlert()"
                        class="px-8 py-3 rounded-xl bg-[#123f77] text-white hover:bg-[#0f86b6] font-semibold shadow-lg transition-transform hover:scale-105">
                        Mulai Jelajah
                    </button>
                @endauth
            </div>
        </div>
    </section>
</x-main>
