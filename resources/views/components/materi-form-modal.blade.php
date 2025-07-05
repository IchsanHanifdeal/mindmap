<dialog id="tambah_materi_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box bg-white text-gray-800">
        <h3 class="text-lg font-bold text-[#123f77]">Tambah Materi</h3>
        <form method="POST" action="{{ route('materi.store') }}" enctype="multipart/form-data" class="mt-4 space-y-4"
            id="materiForm">
            @csrf
            <div>
                <label class="block mb-1 text-sm font-medium">Nama Materi</label>
                <input type="text" name="nama_materi" required
                    class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#123f77]"
                    placeholder="Masukkan nama materi">
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Deskripsi</label>
                <div id="quill-editor" class="bg-white border rounded-lg p-2 min-h-[150px]"></div>
                <input type="hidden" name="deskripsi" id="deskripsi">
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Tipe File</label>
                <select name="tipe_file" id="tipe_file"
                    class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#123f77]">
                    <option value="text">Text</option>
                    <option value="dokumen">Dokumen (PDF)</option>
                </select>
            </div>

            <div id="upload_wrapper" class="hidden">
                <label class="block mb-1 text-sm font-semibold text-gray-700">Upload File (PDF)</label>
                <div class="flex items-center gap-4 bg-white border border-gray-300 rounded-lg p-4 shadow-sm">
                    <x-lucide-upload class="text-[#123f77] w-6 h-6 shrink-0" />
                    <input type="file" name="file" accept="application/pdf"
                        class="file-input file-input-bordered file-input-sm w-full text-gray-800" />
                </div>
                <p class="text-xs text-gray-500 mt-1">Hanya file PDF yang diizinkan. Ukuran maksimal sesuai konfigurasi
                    server.</p>
            </div>

            <div class="modal-action">
                <button type="button" onclick="tambah_materi_modal.close()"
                    class="btn bg-gray-100 text-gray-700 border border-gray-300 hover:bg-gray-200 hover:text-black transition">
                    Batal
                </button>

                <button type="submit" onclick="closeAllModals(event)"
                    class="btn bg-[#123f77] hover:bg-[#0e2f56] text-white transition">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</dialog>
