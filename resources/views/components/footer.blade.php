@php
    $isHome = request()->is('/');
@endphp

<footer class="p-5 border-t border-gray-200 bg-white text-center">
    <div class="container mx-auto flex justify-between items-center text-sm text-[#123f77]">
        <span>&copy; {{ date('Y') }} Mindmapku™. All Rights Reserved.</span>
    </div>
</footer>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('mindmapSidebar', () => ({
            mindmapTitle: '🧠 MindMap Tools',

            editTitle() {
                Swal.fire({
                    title: 'Edit Judul Mindmap',
                    input: 'text',
                    inputLabel: 'Judul Mindmap',
                    inputValue: this.mindmapTitle.replace('🧠 ', ''),
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Batal',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Judul tidak boleh kosong!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.mindmapTitle = '🧠 ' + result.value;

                    }
                });
            }
        }));
    });
</script>

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
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const options = {
            container: 'jsmind_container',
            editable: true,
            theme: 'primary',
        };

        const mind = {
            meta: {
                name: "demo",
                author: "Mindmapku",
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
        jm.show(mind);

        window.jm = jm;
    });
</script>
<script>
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
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jm = window.jm;

        document.querySelector('[data-action="zoom-in"]').addEventListener('click', function() {
            jm.view.zoomIn();
        });

        document.querySelector('[data-action="zoom-out"]').addEventListener('click', function() {
            jm.view.zoomOut();
        });

    });
</script>

<script>
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
</script>

<script>
    const defaultMind = {
        meta: {
            name: "demo",
            author: "Mindmapku",
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
</script>

<script>
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
</script>
<script>
    function openUpdateModal(soalId) {
        const modal = document.getElementById('update_modal_' + soalId);
        if (modal) {
            modal.showModal(); // Tampilkan modal
        }
    }
</script>
<script>
    function closeAllModals(event) {
        const form = event.target.closest('form');

        if (form) {
            form.submit();

            const modals = document.querySelectorAll('dialog.modal');

            modals.forEach(modal => {
                if (modal.hasAttribute('open')) {
                    modal.close();
                }
            });
        }
    }
</script>

<script>
    document.getElementById('logoutButton').addEventListener('click', function(event) {
        event.preventDefault(); // Mencegah form logout langsung

        // Menampilkan modal konfirmasi logout
        document.getElementById('logoutModal').showModal();
    });

    document.querySelector('#logoutModal button[type="button"]').addEventListener('click', function() {
        document.getElementById('logoutModal').close(); // Menutup modal saat tombol Batal diklik
    });

    document.querySelector('#logoutModal form').addEventListener('submit', function(event) {
        event.preventDefault(); // Mencegah form logout langsung
        document.getElementById('logout-form').submit(); // Submit form logout setelah konfirmasi
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        AOS.init({
            once: false, // Reverse aktif
            duration: 1000,
            easing: "ease-in-out",
            anchorPlacement: "top-center",
        });
    });
</script>
