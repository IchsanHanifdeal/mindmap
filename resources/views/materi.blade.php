<x-main title="Materi" class="p-0 min-h-screen flex flex-col">
    <style>
        .swal2-container.swal2-top-end {
            z-index: 9999 !important;
        }
    </style>
    <x-home.navbar />

    <section class="flex-grow bg-gradient-to-br from-[#f0f6ff] to-[#e0ebff] px-6 py-12">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-10">
                <h1 class="text-4xl font-extrabold tracking-tight text-[#123f77]">Overview</h1>

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
                                    <div class="tooltip" data-tip="Catat Highlight">
                                        <button type="button" onclick="showKataKunciModal({{ $item->id }})"
                                            class="btn btn-sm btn-primary flex items-center gap-1">
                                            <x-lucide-book-key class="w-4 h-4" />
                                        </button>
                                    </div>
                                    <div class="tooltip" data-tip="Buat Mindmap dari Materi">
                                        <button type="button" onclick="makeMindmap({{ $item->id }})"
                                            class="btn btn-sm bg-blue-500 flex items-center gap-1 hover:bg-blue-700">
                                            <x-lucide-pencil-ruler class="w-4 h-4" />
                                        </button>
                                    </div>
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

    <dialog id="pdf_viewer_modal" class="modal hidden">
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
            document.getElementById('pdf_viewer_modal').classList.remove('hidden');
            const modal = document.getElementById('pdf_viewer_modal');
            const iframe = document.getElementById('pdf_viewer_iframe');
            iframe.style.opacity = 0;
            iframe.src = '/pdfjs/web/viewer.html?file=' + encodeURIComponent(url);
            modal.showModal();
            iframe.onload = () => iframe.style.opacity = 1;
        }

        window.addEventListener('message', async (event) => {
            if (event.data.action === 'add_kata_kunci') {
                await addKataKunciFromHighlight(event.data.text);
            }
        });

        function closePdfModal() {
            document.getElementById('pdf_viewer_modal').classList.add('hidden');
            const modal = document.getElementById('pdf_viewer_modal');
            document.getElementById('pdf_viewer_iframe').src = '';
            modal.close();
        }

        async function addKataKunciFromHighlight(text) {
            if (!text || !currentMateriId) return;

            await fetch('/api/kata-kunci', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    materi: currentMateriId,
                    user: userId,
                    kata_kunci: text
                })
            });

            await refreshTable();
            showToastSuccess(`"${text}" berhasil ditambahkan sebagai Highlight`);
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

        // Highlight Modal
        const userId = {{ Auth::user()->id }};
        let currentMateriId = null;
        let kataKunciList = [];

        async function loadKataKunci() {
            const res = await fetch(`/api/kata-kunci?materi=${currentMateriId}&user=${userId}`);
            kataKunciList = await res.json();
        }

        function renderTableRows() {
            if (kataKunciList.length === 0) {
                return `
            <tr>
                <td colspan="3" class="text-center text-gray-500 italic">Belum ada Highlight tercatat</td>
            </tr>
        `;
            }

            return kataKunciList.map((kk, index) => `
        <tr>
            <td>${index + 1}</td>
            <td class="capitalize">${kk.kata_kunci}</td>
            <td class="flex gap-1">
                <button class="btn btn-xs btn-warning" onclick="editKataKunci(${kk.id})">
                    <x-lucide-pencil class="w-4 h-4" />
                </button>
                <button class="btn btn-xs btn-error" onclick="deleteKataKunci(${kk.id})">
                    <x-lucide-trash-2 class="w-4 h-4" />
                </button>
            </td>
        </tr>
    `).join('');
        }

        async function refreshTable() {
            try {
                await loadKataKunci();

                const tbody = Swal.getPopup()?.querySelector('#kataKunciTableBody');
                if (!tbody) return;

                tbody.innerHTML = renderTableRows();
            } catch (error) {
                console.error('Gagal memuat Highlight:', error);
                showToastError('Gagal memuat daftar Highlight');
            }
        }

        async function showKataKunciModal(materiId) {
            currentMateriId = materiId;
            await loadKataKunci();

            Swal.fire({
                title: '<b>Catat Highlight</b>',
                html: `
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body p-2">
                        <div class="flex gap-2">
                            <input id="newKataKunci" type="text" placeholder="Masukkan Highlight"
                                class="input input-bordered flex-1" />
                            <button id="addKataKunciBtn" class="btn btn-success">
                                <i class="lucide lucide-plus"></i> Tambah
                            </button>
                        </div>

                        <div class="overflow-x-auto mt-3 max-h-52 overflow-y-auto">
                            <table class="table table-zebra">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Highlight</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="kataKunciTableBody">
                                    ${renderTableRows()}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `,
                showConfirmButton: false,
                width: '500px',
                didOpen: () => {
                    const addBtn = Swal.getPopup().querySelector('#addKataKunciBtn');
                    addBtn.addEventListener('click', async () => {
                        const input = Swal.getPopup().querySelector('#newKataKunci');
                        const value = input.value.trim();
                        if (!value) return;

                        await fetch('/api/kata-kunci', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                materi: currentMateriId,
                                user: userId,
                                kata_kunci: value
                            })
                        });

                        input.value = '';
                        await refreshTable();
                        showToastSuccess('Highlight berhasil ditambahkan');
                    });

                }
            });
        }

        async function editKataKunci(id) {
            const kk = kataKunciList.find(k => k.id === id);
            if (!kk) return;

            await Swal.fire({
                title: '<b>Edit Highlight</b>',
                html: `
            <div class="flex flex-col gap-2">
                <input id="editKataKunciInput" type="text" value="${kk.kata_kunci}"
                    class="input input-bordered w-full" />
                <button id="saveEditBtn" class="btn btn-primary w-full">
                    <i class="lucide lucide-save mr-1"></i> Simpan
                </button>
            </div>
        `,
                showConfirmButton: false,
                width: '400px',
                didOpen: () => {
                    const saveBtn = Swal.getPopup().querySelector('#saveEditBtn');
                    saveBtn.addEventListener('click', async () => {
                        const input = Swal.getPopup().querySelector('#editKataKunciInput');
                        const newVal = input.value.trim();
                        if (!newVal) {
                            Swal.showValidationMessage('Highlight tidak boleh kosong');
                            return;
                        }

                        await fetch(`/api/kata-kunci/${id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                kata_kunci: newVal
                            })
                        });

                        Swal.close();
                        await refreshTable();
                        showToastSuccess('Highlight berhasil diubah');
                    });

                }
            });
        }


        async function deleteKataKunci(id) {
            const {
                isConfirmed
            } = await Swal.fire({
                title: 'Hapus Highlight?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus'
            });

            if (isConfirmed) {
                await fetch(`/api/kata-kunci/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                await refreshTable();
                showToastSuccess('Highlight berhasil dihapus');
            }

        }

        function showToastSuccess(message) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
        }

        function makeMindmap() {
            Swal.fire({
                title: 'Pilih Jenis Mindmap',
                input: 'select',
                inputOptions: {
                    spider: 'Spider Map',
                    flow: 'Flow Map',
                    multi: 'Multi-flow Map',
                    bubble: 'Bubble Map',
                    brace: 'Brace Map',
                    custom: 'Custom Map'
                },
                inputPlaceholder: 'Pilih satu jenis',
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const selectedType = result.value;
                    window.location.href = `/mindmap/${selectedType}`;
                }
            });
        }
    </script>
</x-main>
