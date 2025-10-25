<x-mindmap.main title="Brace Mindmap" class="p-0">
    <div class="flex w-full h-screen">
        {{-- Bagian Mindmap --}}
        <div id="jsmind_container"
            class="relative flex-1 bg-[length:40px_40px] bg-[linear-gradient(to_right,rgba(0,0,0,0.1)_1px,transparent_1px),linear-gradient(to_bottom,rgba(0,0,0,0.1)_1px,transparent_1px)]">

            {{-- Gambar di kanan atas --}}
            <div class="absolute top-2 right-2 w-48 h-48 bg-white p-2 rounded shadow-md cursor-pointer z-50"
                onclick="showImageModal('{{ asset('img/brace.svg') }}')">
                <img src="{{ asset('img/brace.svg') }}" alt="Gambar"
                    class="w-full h-full object-contain transition-transform duration-200 hover:scale-105" />
            </div>

        </div>
    </div>
</x-mindmap.main>

<script>
    async function addNode() {
        let selected_node = window.jm.get_selected_node();

        if (!selected_node) {
            const root_node = window.jm.get_root();
            const {
                isConfirmed
            } = await Swal.fire({
                icon: 'question',
                title: 'Tidak Ada Node Dipilih',
                text: 'Node baru akan ditambahkan ke pusat mindmap. Lanjutkan?',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal'
            });

            if (!isConfirmed) return;
            selected_node = root_node;
        }

        const {
            value: topic
        } = await Swal.fire({
            title: 'Tambah Node Baru',
            input: 'text',
            inputLabel: 'Nama Node',
            inputPlaceholder: 'Misalnya: Ide Baru',
            showCancelButton: true,
            confirmButtonText: 'Tambah',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) {
                    return 'Nama node tidak boleh kosong!';
                }
            }
        });

        if (topic) {
            const nodeid = jsMind.util.uuid.newid();
            window.jm.add_node(selected_node, nodeid, topic);
            pushToHistory();
            await Swal.fire({
                icon: 'success',
                title: 'Node Ditambahkan!',
                timer: 1000,
                showConfirmButton: false
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const options = {
            container: 'jsmind_container',
            editable: true,
            theme: 'primary',
        };

        const mind = {
            meta: {
                name: "demo",
                author: "Digital Mind Mapping OPIRSURE",
                version: "1.0"
            },
            format: "node_array",
            data: [{
                    id: "root",
                    isroot: true,
                    topic: "Topik Utama"
                },
                {
                    id: "sub1",
                    parentid: "root",
                    topic: "Subtopik 1"
                },
                {
                    id: "sub2",
                    parentid: "root",
                    topic: "Subtopik 2"
                }
            ]
        };

        const jm = new jsMind(options);
        jm.show({
            meta: mind.meta,
            format: 'node_array',
            data: mind.data
        });

        window.jm = jm;
    });

    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("btn-export-png")?.addEventListener("click", () => exportMindmap('png'));
        document.getElementById("btn-export-pdf")?.addEventListener("click", () => exportMindmap('pdf'));
    });

    async function exportMindmap(type = 'png') {
        const jm = window.jm;
        if (!jm || !jm.screenshot || typeof jm.screenshot.shoot !== 'function') {
            await Swal.fire('Gagal', 'Mindmap belum siap atau fitur ekspor tidak tersedia.', 'error');
            return;
        }

        Swal.fire({
            title: 'Mengekspor...',
            html: 'Tunggu sebentar...',
            didOpen: () => Swal.showLoading(),
            allowOutsideClick: false
        });

        try {
            jm.screenshot.shoot(async function(dataUrl) {
                if (!dataUrl || !dataUrl.startsWith('data:image/')) {
                    await Swal.fire('Gagal menghasilkan gambar', 'Format data tidak valid.', 'error');
                    return;
                }

                const filename = `mindmap-${dayjs().format('YYYY-MM-DD_HH-mm')}`;

                if (type === 'png') {
                    const link = document.createElement('a');
                    link.href = dataUrl;
                    link.download = filename + '.png';
                    link.click();
                    Swal.close();
                } else if (type === 'pdf') {
                    const {
                        jsPDF
                    } = window.jspdf;
                    const pdf = new jsPDF({
                        orientation: 'landscape',
                        unit: 'pt',
                        format: 'a4'
                    });

                    const img = new Image();
                    img.onload = function() {
                        const pdfWidth = pdf.internal.pageSize.getWidth();
                        const pdfHeight = (img.height * pdfWidth) / img.width;

                        pdf.addImage(dataUrl, 'PNG', 0, 0, pdfWidth, pdfHeight);
                        pdf.save(filename + '.pdf');
                        Swal.close();
                    };
                    img.onerror = function() {
                        Swal.fire('Export Gagal', 'Gagal memuat gambar untuk PDF.', 'error');
                    };
                    img.src = dataUrl;
                }
            }, {
                backgroundColor: '#ffffff'
            });
        } catch (err) {
            console.error(err);
            await Swal.fire('Export Gagal', err.message || 'Terjadi kesalahan saat ekspor.', 'error');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const jm = window.jm;

        document.querySelector('[data-action="zoom-in"]').addEventListener('click', function() {
            jm.view.zoomIn();
        });

        document.querySelector('[data-action="zoom-out"]').addEventListener('click', function() {
            jm.view.zoomOut();
        });

    });

    const historyStack = [];

    function pushToHistory() {
        if (window.jm) {
            const snapshot = window.jm.get_data(); // Mendapatkan struktur data
            historyStack.push(JSON.parse(JSON.stringify(snapshot))); // Deep clone
        }
    }

    function undoMindmap() {
        if (historyStack.length === 0) {
            Swal.fire("Tidak ada perubahan", "Belum ada perubahan untuk di-undo.", "info");
            return;
        }

        const lastState = historyStack.pop();
        if (lastState && window.jm) {
            window.jm.show(lastState);
            Swal.fire("Undo Berhasil", "", "success");
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        const undoBtn = document.querySelector('[data-action="undo"]');
        if (!undoBtn) return;

        undoBtn.addEventListener('click', undoMindmap);
    });

    const defaultMind = {
        meta: {
            name: "demo",
            author: "Digital Mind Mapping OPIRSURE",
            version: "1.0"
        },
        format: "node_array",
        data: [{
                id: "root",
                isroot: true,
                topic: "Topik Utama"
            },
            {
                id: "sub1",
                parentid: "root",
                topic: "Subtopik 1"
            },
            {
                id: "sub2",
                parentid: "root",
                topic: "Subtopik 2"
            }
        ]
    };

    document.addEventListener("DOMContentLoaded", () => {
        const resetButton = document.querySelector('[data-action="reset"]');
        if (!resetButton) return;

        resetButton.addEventListener('click', function() {
            Swal.fire({
                title: 'Reset Mindmap?',
                text: "Semua perubahan akan hilang.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, reset',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (window.jm && typeof window.jm.show === 'function') {
                        window.jm.show(defaultMind);
                        Swal.fire("Reset Berhasil",
                            "Mindmap telah dikembalikan ke kondisi awal.", "success");
                    } else {
                        Swal.fire("Gagal", "Mindmap tidak tersedia atau belum dimuat.",
                            "error");
                    }
                }
            });
        });
    });

    document.addEventListener("DOMContentLoaded", () => {
        const button = document.getElementById('btn-generate-summary');

        button?.addEventListener('click', async () => {
            console.log("Tombol diklik");

            const mindmap = window.jm?.get_data?.('node_array');
            console.log("Mindmap:", mindmap);

            if (!mindmap || mindmap.length === 0) {
                return Swal.fire("Gagal", "Mindmap kosong atau tidak valid.", "error");
            }

            Swal.fire({
                title: 'Menghasilkan ringkasan...',
                html: 'Tunggu sebentar...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const res = await fetch('/mindmap/generate-summary', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            ?.content || ''
                    },
                    body: JSON.stringify({
                        mindmap
                    })
                });

                if (!res.ok) {
                    throw new Error(`Status ${res.status}`);
                }

                const result = await res.json();
                const summary = result.summary || 'Tidak ada ringkasan.';

                Swal.fire({
                    title: 'Ringkasan Mindmap 📚',
                    html: `
                    <div style="text-align:left;">
                        <details open style="margin-bottom:10px;">
                            <summary><strong>🧠 Ringkasan Lengkap</strong></summary>
                            <div style="margin-top:8px;white-space:pre-wrap;">${summary}</div>
                        </details>
                        <p style="font-size:0.9em;color:#666;">Ringkasan dihasilkan otomatis menggunakan AI.</p>
                    </div>
                `,
                    width: '65%',
                    customClass: {
                        popup: 'animated fadeInUp faster'
                    },
                    confirmButtonText: 'Selesai',
                    showCloseButton: true,
                    focusConfirm: false
                });

            } catch (err) {
                console.error("Fetch error:", err);
                Swal.fire('Gagal', 'Terjadi kesalahan saat memanggil server.', 'error');
            }
        });
    });

    document.addEventListener("DOMContentLoaded", () => {
        const saveButton = document.getElementById('btn-save-mindmap');
        if (!saveButton) return;

        saveButton.addEventListener('click', async () => {
            const titlePrompt = await Swal.fire({
                title: 'Judul Mindmap',
                input: 'text',
                inputPlaceholder: 'Masukkan judul mindmap',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                inputValidator: value => (!value ? 'Judul wajib diisi' : undefined)
            });

            if (!titlePrompt.isConfirmed) return;

            const title = titlePrompt.value.trim() || 'Untitled';
            const type = window.location.pathname.split('/').pop() || 'brace';

            // Perbaikan: ambil .data dari get_data('node_array')
            const rawMindmap = window.jm?.get_data?.('node_array');
            console.log("DEBUG mindmapData:", rawMindmap); // Debug
            const mindmapData = rawMindmap?.data || [];

            if (!Array.isArray(mindmapData) || mindmapData.length === 0) {
                return Swal.fire("Gagal", "Mindmap kosong atau tidak valid.", "error");
            }

            await Swal.fire({
                title: 'Menyimpan Mindmap...',
                html: `<div id="swal-log">⏳ Memulai penyimpanan...</div>`,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });

            const log = (msg) => {
                const el = document.getElementById('swal-log');
                if (el) el.innerHTML += `<br>• ${msg}`;
            };

            try {
                log('📸 Mengambil screenshot...');
                const pngData = captureMindmapImage(); // pastikan fungsi ini ada

                if (!pngData || !pngData.startsWith('data:image/')) {
                    throw new Error("Data gambar tidak valid.");
                }

                log('📤 Mengirim data ke server...');

                $.ajax({
                    url: '/mindmap/save',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({
                        title,
                        type,
                        mindmap: mindmapData,
                        image: pngData
                    }),
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Mindmap berhasil disimpan!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        const errText = xhr.responseText ||
                            'Terjadi kesalahan saat menyimpan.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan',
                            html: `<pre style="text-align:left;color:#c00;">${errText}</pre>`,
                            confirmButtonText: 'Tutup'
                        });
                    }
                });

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    html: `<strong>${error.message}</strong>`,
                    confirmButtonText: 'Tutup'
                });
            }
        });
    });
</script>
