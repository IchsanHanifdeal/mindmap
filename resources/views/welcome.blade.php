<x-main class="p-0 min-h-screen flex flex-col">
    <x-home.navbar />

    <section
        class="flex-grow flex items-center justify-center bg-gradient-to-br from-[#f0f6ff] to-[#e0ebff] px-6 text-center">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-extrabold text-[#123f77] leading-tight">
                Digital Mind Mapping untuk Pembaca Kritis
            </h1>
            <p class="mt-5 text-lg md:text-xl text-gray-700">
                Visualisasikan ide, rangkum bacaan, dan refleksikan pemahamanmu dalam satu platform edukatif yang
                interaktif.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                @auth
                    <a href="{{ route('materi') }}"
                        class="btn bg-[#123f77] hover:bg-[#0f86b6] text-white font-semibold px-6 py-3 rounded-xl">
                        Overview
                    </a>
                @else
                    <button onclick="showLoginAlert()"
                        class="btn bg-[#123f77] hover:bg-[#0f86b6] text-white font-semibold px-6 py-3 rounded-xl">
                        Overview
                    </button>
                @endauth
            </div>
        </div>
    </section>
</x-main>
