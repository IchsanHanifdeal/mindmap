<x-main title="Materi" class="p-0 min-h-screen flex flex-col">
    <x-home.navbar />

    <section class="flex-grow bg-gradient-to-br from-[#f0f6ff] to-[#e0ebff] px-6 py-12">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-10">
                <h1 class="text-4xl font-extrabold tracking-tight text-[#123f77]">Perpustakaan Materi</h1>

                @if (Auth::user()->role === 'admin')
                    <button onclick="tambah_materi_modal.showModal()" class="btn btn-primary rounded-xl shadow-md gap-2">
                        <x-lucide-plus class="size-5" /> Tambah Materi
                    </button>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($materi as $item)
                    <div class="flex flex-col bg-white border rounded-xl shadow-sm hover:shadow-lg transition h-full">
                        <!-- Gambar / Preview -->
                        <figure class="relative h-52 bg-white border-b rounded-t-xl overflow-hidden group">
                            @if ($item->tipe_file === 'dokumen' && $item->file)
                                <canvas data-pdf="{{ asset('storage/materi/' . $item->file) }}"
                                    class="pdf-thumbnail w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"></canvas>
                                <div
                                    class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm rounded-full p-1 shadow">
                                    <x-lucide-file-text class="w-5 h-5 text-[#123f77]" />
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center w-full h-full text-gray-400 px-4">
                                    <x-lucide-align-left class="w-10 h-10 mb-1" />
                                    <span class="text-sm text-center">Konten Teks</span>
                                </div>
                            @endif
                        </figure>

                        <!-- Konten -->
                        <div class="flex flex-col justify-between flex-grow p-5 space-y-3">
                            <div>
                                <h2 class="text-[#123f77] font-semibold text-lg leading-snug mb-1">
                                    {{ $item->nama_materi }}
                                </h2>

                                <div class="text-gray-600 text-sm line-clamp-3">
                                    {!! $item->deskripsi !!}
                                </div>

                            </div>
                            @if (Auth::user()->role === 'user')
                                <div class="flex flex-wrap justify-end gap-2 pt-4 border-t">
                                    @if ($item->tipe_file === 'dokumen' && $item->file)
                                        <div class="tooltip" data-tip="Lihat Dokumen">
                                            <button type="button"
                                                onclick="openPdfModal('{{ asset('storage/materi/' . $item->file) }}')"
                                                class="btn btn-sm btn-warning flex items-center gap-1">
                                                <x-lucide-eye class="w-4 h-4" />
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @elseif (Auth::user()->role === 'admin')
                                <div class="flex flex-wrap justify-end gap-2 pt-4 border-t">
                                    @if ($item->tipe_file === 'dokumen' && $item->file)
                                        <div class="tooltip" data-tip="Lihat Dokumen">
                                            <button type="button"
                                                onclick="openPdfModal('{{ asset('storage/materi/' . $item->file) }}')"
                                                class="btn btn-sm btn-warning flex items-center gap-1">
                                                <x-lucide-eye class="w-4 h-4" />
                                            </button>
                                        </div>
                                    @endif

                                    <div class="tooltip" data-tip="Edit Materi">
                                        <button type="button"
                                            onclick="editMateri('{{ $item->id }}', '{{ addslashes($item->judul) }}', '{{ addslashes($item->deskripsi) }}')"
                                            class="btn btn-sm btn-primary flex items-center gap-1">
                                            <x-lucide-pencil class="w-4 h-4" />
                                        </button>
                                    </div>

                                    <div class="tooltip" data-tip="Hapus Materi">
                                        <button type="button" onclick="confirmDelete({{ $item->id }})"
                                            class="btn btn-sm btn-error flex items-center gap-1">
                                            <x-lucide-trash-2 class="w-4 h-4" />
                                        </button>
                                    </div>

                                    <form id="delete-form-{{ $item->id }}" method="POST"
                                        action="{{ route('materi.destroy', $item->id) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <dialog id="text_viewer_modal" class="modal">
        <div class="modal-box max-w-3xl bg-white rounded-xl shadow-lg p-6 space-y-4">
            <h3 class="text-xl font-semibold text-[#123f77]">Detail Materi</h3>
            <div id="text_viewer_content" class="prose max-h-[60vh] overflow-y-auto text-gray-700">
            </div>
            <form method="dialog" class="text-right">
                <button class="btn btn-sm btn-primary mt-4">Tutup</button>
            </form>
        </div>
    </dialog>

    <dialog id="pdf_viewer_modal" class="modal">
        <div class="modal-box w-full max-w-6xl h-[90vh] p-0 overflow-hidden bg-base-100 rounded-xl">
            <div class="flex justify-between items-center px-4 py-3 bg-[#123f77] text-white border-b">
                <h3 class="font-bold text-lg">Pratinjau Dokumen</h3>
                <form method="dialog">
                    <button onclick="closePdfModal()"
                        class="btn btn-sm btn-circle btn-ghost hover:bg-red-500 hover:text-white">✕</button>
                </form>
            </div>
            <div class="w-full h-full bg-gray-200">
                <iframe id="pdf_viewer_iframe"
                    class="w-full h-[80vh] bg-white transition-opacity duration-200 ease-in-out rounded-b-lg"
                    src="" frameborder="0"></iframe>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button onclick="closePdfModal()">close</button>
        </form>
    </dialog>

    @if (Auth::user()->role === 'admin')
        @include('components.materi-form-modal')
    @endif

    <!-- Scripts -->
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        function editMateri(id, judul, deskripsi) {
            Swal.fire({
                title: 'Edit Materi',
                html: `
                <input id="edit-judul" class="swal2-input" placeholder="Judul" value="${judul}">
                <div id="quill-editor-swal" style="height: 200px;"></div>
            `,
                width: 700,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                didOpen: () => {
                    const quill = new Quill('#quill-editor-swal', {
                        theme: 'snow',
                        placeholder: 'Deskripsi...',
                        modules: {
                            toolbar: [
                                ['bold', 'italic'],
                                ['link'],
                                [{
                                    list: 'ordered'
                                }, {
                                    list: 'bullet'
                                }]
                            ]
                        }
                    });
                    quill.root.innerHTML = deskripsi;
                    Swal.setData({
                        quill
                    });
                },
                preConfirm: () => {
                    const quill = Swal.getData().quill;
                    return {
                        judul: document.getElementById('edit-judul').value,
                        deskripsi: quill.root.innerHTML
                    };
                }
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/materi/${id}/update`;
                    form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="judul" value="${result.value.judul}">
                    <input type="hidden" name="deskripsi" value="${encodeURIComponent(result.value.deskripsi)}">
                `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function openPdfModal(url) {
            const modal = document.getElementById('pdf_viewer_modal');
            const iframe = document.getElementById('pdf_viewer_iframe');
            iframe.style.opacity = 0;
            iframe.src = '/pdfjs/web/viewer.html?file=' + encodeURIComponent(url);
            modal.showModal();
            iframe.onload = () => iframe.style.opacity = 1;
        }

        function closePdfModal() {
            const modal = document.getElementById('pdf_viewer_modal');
            const iframe = document.getElementById('pdf_viewer_iframe');
            iframe.src = '';
            modal.close();
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Materi yang dihapus tidak dapat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }

        function openTextModal(text) {
            const modal = document.getElementById('text_viewer_modal');
            const content = document.getElementById('text_viewer_content');
            content.innerHTML = text;
            modal.showModal();
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Render thumbnail PDF
            const canvases = document.querySelectorAll('.pdf-thumbnail');
            canvases.forEach(canvas => {
                const url = canvas.dataset.pdf;
                const loadingTask = pdfjsLib.getDocument(url);
                loadingTask.promise.then(pdf => {
                    pdf.getPage(1).then(page => {
                        const viewport = page.getViewport({
                            scale: 1.2
                        });
                        const context = canvas.getContext('2d');
                        canvas.width = viewport.width;
                        canvas.height = viewport.height;
                        page.render({
                            canvasContext: context,
                            viewport: viewport
                        });
                    });
                }).catch(err => console.error('Gagal memuat PDF:', err));
            });

            // Init Quill untuk form utama jika ada
            const quillContainer = document.getElementById('quill-editor');
            if (quillContainer) {
                const quill = new Quill(quillContainer, {
                    theme: 'snow',
                    placeholder: 'Tulis deskripsi materi di sini...'
                });

                const form = document.getElementById('materiForm');
                if (form) {
                    form.addEventListener('submit', function() {
                        document.getElementById('deskripsi').value = quill.root.innerHTML;
                    });
                }
            }

            // Toggle dokumen upload input
            const tipeFileSelect = document.getElementById('tipe_file');
            const uploadWrapper = document.getElementById('upload_wrapper');

            function toggleUpload() {
                if (tipeFileSelect && uploadWrapper) {
                    uploadWrapper.classList.toggle('hidden', tipeFileSelect.value !== 'dokumen');
                }
            }
            if (tipeFileSelect) {
                tipeFileSelect.addEventListener('change', toggleUpload);
            }
            toggleUpload();
        });
    </script>
</x-main>
