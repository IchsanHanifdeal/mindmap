<div class="drawer-side border-r border-gray-200 shadow-md z-20">
    <label for="aside-dashboard" aria-label="Close sidebar" class="drawer-overlay"></label>

    <ul
        class="bg-white menu flex flex-col justify-between p-4 w-64 lg:w-72 min-h-full text-[#123f77] text-[14px] font-medium [&>li]:my-1.5 [&>li>button]:gap-3">

        <div>
            <!-- Title -->
            <div x-data="mindmapSidebar" class="pb-4 border-b border-[#123f77]">
                <div class="flex justify-between items-center">
                    <span x-text="mindmapTitle" class="text-xl font-bold cursor-pointer" @click="editTitle()"></span>
                    <button class="btn btn-sm btn-outline border-[#123f77] text-[#123f77] hover:bg-[#f0f6ff]"
                        @click="editTitle()">Ubah</button>
                </div>
            </div>

            <!-- NODE SECTION -->
            <span class="label text-xs font-bold mt-4">NODE</span>
            <li>
                <button class="flex items-center px-2.5 py-2 hover:bg-[#f0f6ff] rounded" onclick="addNode()">
                    <x-lucide-plus-circle class="w-5 h-5 stroke-2" /> Tambah Node
                </button>
            </li>

            <!-- PROSES -->
            <span class="label text-xs font-bold mt-4">PROSES</span>
            <li>
                <button type="button" id="btn-generate-summary" class="flex items-center px-2.5 py-2 hover:bg-[#f0f6ff] rounded">
                    <x-lucide-brain-circuit class="w-5 h-5 stroke-2" /> Generate Ringkasan
                </button>
            </li>

            <li>
                <button id="btn-export-png" class="flex items-center px-2.5 py-2 hover:bg-[#f0f6ff] rounded">
                    <x-lucide-download class="w-5 h-5 stroke-2" /> Export PNG
                </button>
            </li>

            <!-- TAMPILAN -->
            <span class="label text-xs font-bold mt-4">TAMPILAN</span>
            <li><button data-action="zoom-in" class="flex items-center px-2.5 py-2 hover:bg-[#f0f6ff] rounded">
                    <x-lucide-zoom-in class="w-5 h-5 stroke-2" /> Zoom In
                </button></li>

            <li><button data-action="zoom-out" class="flex items-center px-2.5 py-2 hover:bg-[#f0f6ff] rounded">
                    <x-lucide-zoom-out class="w-5 h-5 stroke-2" /> Zoom Out
                </button></li>
            <li><button class="flex items-center px-2.5 py-2 hover:bg-[#f0f6ff] rounded">
                    <x-lucide-save class="w-5 h-5 stroke-2" /> Simpan Mindmap</button></li>

            <!-- LAINNYA -->
            <span class="label text-xs font-bold mt-4">LAINNYA</span>
            {{-- <li><button data-action="undo" class="flex items-center px-2.5 py-2 hover:bg-[#f0f6ff] rounded">
                    <x-lucide-undo class="w-5 h-5 stroke-2" /> Undo
                </button></li> --}}

            <li><button data-action="reset" class="flex items-center px-2.5 py-2 hover:bg-[#f0f6ff] rounded">
                    <x-lucide-refresh-ccw class="w-5 h-5 stroke-2" /> Reset Mindmap
                </button></li>
        </div>
    </ul>
</div>
