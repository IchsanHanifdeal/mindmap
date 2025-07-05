<x-mindmap.main title="Flow Mindmap" class="p-0">
    <div class="flex w-full h-screen">
        {{-- Bagian Mindmap --}}
        <div id="jsmind_container"
            class="relative flex-1 overflow-auto bg-[length:40px_40px] bg-[linear-gradient(to_right,rgba(0,0,0,0.1)_1px,transparent_1px),linear-gradient(to_bottom,rgba(0,0,0,0.1)_1px,transparent_1px)]">

            {{-- Gambar di kanan atas --}}
            <div class="absolute top-2 right-2 w-48 h-48 bg-white p-2 rounded shadow-md cursor-pointer z-50"
                onclick="showImageModal('{{ asset('img/flow.jpg') }}')">
                <img src="{{ asset('img/flow.jpg') }}" alt="Gambar"
                    class="w-full h-full object-contain transition-transform duration-200 hover:scale-105" />
            </div>

        </div>
    </div>
</x-mindmap.main>

<script src="https://unpkg.com/cytoscape@3.28.0/dist/cytoscape.min.js"></script>
<script>
    const cy = cytoscape({
        container: document.getElementById('jsmind_container'),
        style: [{
                selector: 'node',
                style: {
                    'background-color': '#0ea5e9',
                    'label': 'data(label)',
                    'color': '#fff',
                    'text-valign': 'center',
                    'text-halign': 'center',
                    'font-size': 14,
                    'shape': 'data(shape)',
                    'text-wrap': 'wrap',
                    'padding': '10px',
                    'width': 'label',
                    'height': 'data(height)'
                }
            },
            {
                selector: 'node:selected',
                style: {
                    'border-width': 4,
                    'border-color': '#facc15',
                    'shadow-blur': 10,
                    'shadow-color': '#facc15',
                    'shadow-offset-x': 0,
                    'shadow-offset-y': 0,
                    'shadow-opacity': 0.8
                }
            },
            {
                selector: 'edge',
                style: {
                    'width': 2,
                    'line-color': '#94a3b8',
                    'target-arrow-color': '#94a3b8',
                    'target-arrow-shape': 'triangle',
                    'curve-style': 'bezier'
                }
            }
        ],
        elements: [],
        layout: {
            name: 'preset'
        }
    });

    let nodeCount = 0;
    let selectedNode = null;

    window.addEventListener('keydown', e => {
        if (e.shiftKey && e.key.toLowerCase() === 'n') {
            e.preventDefault();
            addNode();
        }
    });

    async function addNode() {
        const {
            value: formValues
        } = await Swal.fire({
            title: 'Tambah Node',
            html: `
            <div class="grid gap-2">
                <input id="node-label" class="input input-bordered w-full" placeholder="Nama Node" />
                <select id="node-shape" class="select select-bordered">
                    <option value="roundrectangle">Kotak Bulat</option>
                    <option value="ellipse">Elips</option>
                    <option value="rectangle">Kotak</option>
                    <option value="diamond">Berlian</option>
                </select>
                <div class="flex items-center gap-2">
                    <input id="node-bg" type="color" value="#0ea5e9" class="w-10 h-10 border rounded" />
                    <span class="text-sm">Warna Latar</span>
                    <input id="node-color" type="color" value="#ffffff" class="w-10 h-10 border rounded ml-4" />
                    <span class="text-sm">Warna Teks</span>
                </div>
            </div>
        `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Tambah',
            didOpen: () => {
                document.getElementById('node-shape').addEventListener('change', (e) => {
                    const preview = document.getElementById('cloud-preview');
                    preview.classList.toggle('hidden', e.target.value !== 'cloud');
                });
            },
            preConfirm: () => {
                return {
                    label: document.getElementById('node-label').value || 'Node Baru',
                    shape: document.getElementById('node-shape').value,
                    bg: document.getElementById('node-bg').value,
                    color: document.getElementById('node-color').value
                };
            }
        });

        if (!formValues) return;

        const id = 'n' + (++nodeCount);

        const node = {
            group: 'nodes',
            data: {
                id,
                label: formValues.label,
                height: 60,
                shape: formValues.shape === 'cloud' ? 'rectangle' : formValues.shape
            },
            position: {
                x: 100 + Math.random() * 400,
                y: 100 + Math.random() * 400
            },
            style: {
                'background-color': formValues.bg,
                'color': formValues.color,
                ...(formValues.shape === 'cloud' && {
                    'background-image': 'url(https://cdn-icons-png.flaticon.com/512/4146/4146812.png)',
                    'background-fit': 'contain',
                    'background-opacity': 0,
                })
            }
        };

        cy.add(node);
    }

    cy.on('tap', 'node', async function(evt) {
        const node = evt.target;

        if (evt.originalEvent.detail === 2) {
            const {
                value: newLabel
            } = await Swal.fire({
                title: 'Ubah Label Node',
                input: 'text',
                inputValue: node.data('label'),
                showCancelButton: true,
                confirmButtonText: 'Ubah'
            });

            if (newLabel !== undefined) {
                node.data('label', newLabel);
            }

        } else {
            if (selectedNode && selectedNode.id() !== node.id()) {
                const edgeId = `e_${selectedNode.id()}_${node.id()}`;
                cy.add({
                    group: 'edges',
                    data: {
                        id: edgeId,
                        source: selectedNode.id(),
                        target: node.id()
                    }
                });
                selectedNode.unselect();
                selectedNode = null;
            } else {
                cy.nodes().unselect();
                node.select();
                selectedNode = node;
            }
        }
    });

    document.addEventListener('keydown', async function(event) {
        if ((event.key === 'Delete' || event.key === 'Backspace') && selectedNode) {
            const result = await Swal.fire({
                title: 'Hapus Node?',
                text: `Yakin ingin menghapus node "${selectedNode.data('label')}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus'
            });

            if (result.isConfirmed) {
                cy.remove(selectedNode);
                selectedNode = null;
            }
        }
    });

    document.getElementById('btn-export-png').addEventListener('click', function() {
        const padding = 100;
        const pngData = cy.png({
            scale: 4,
            full: true,
            bg: '#ffffff',
            padding: padding
        });

        const link = document.createElement('a');
        link.href = pngData;
        link.download = 'mindmap.png';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Mindmap berhasil diekspor sebagai PNG berkualitas tinggi.',
            timer: 2000,
            showConfirmButton: false
        });
    });


    document.querySelectorAll('[data-action="zoom-in"], [data-action="zoom-out"]').forEach(button => {
        button.addEventListener('click', () => {
            const action = button.getAttribute('data-action');
            const currentZoom = cy.zoom();
            const factor = 0.2;

            if (action === 'zoom-in') {
                cy.zoom({
                    level: currentZoom + factor,
                    renderedPosition: {
                        x: cy.width() / 2,
                        y: cy.height() / 2
                    }
                });
            } else if (action === 'zoom-out') {
                cy.zoom({
                    level: currentZoom - factor,
                    renderedPosition: {
                        x: cy.width() / 2,
                        y: cy.height() / 2
                    }
                });
            }
        });
    });

    document.querySelectorAll('[data-action]').forEach(button => {
        button.addEventListener('click', () => {
            const action = button.getAttribute('data-action');
            const currentZoom = cy.zoom();
            const factor = 0.2;

            switch (action) {
                case 'reset':
                    Swal.fire({
                        title: 'Reset Semua Node?',
                        text: 'Ini akan menghapus semua node dan edge dari mindmap.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus semua',
                        cancelButtonText: 'Batal'
                    }).then(result => {
                        if (result.isConfirmed) {
                            cy.elements().remove();
                            selectedNode = null;
                            nodeCount = 0;

                            Swal.fire({
                                icon: 'success',
                                title: 'Direset',
                                text: 'Semua node berhasil dihapus.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    });
                    break;
            }
        });
    });
    document.addEventListener("DOMContentLoaded", () => {
        const button = document.getElementById('btn-generate-summary');

        button?.addEventListener('click', async () => {
            const nodes = cy.nodes().map(node => ({
                node: node.data('label'),
                parent_node: cy.edges(`[target = "${node.id()}"]`).map(edge => {
                    const sourceId = edge.data('source');
                    const sourceNode = cy.getElementById(sourceId);
                    return sourceNode?.data('label') || null;
                })[0] || null
            }));

            if (nodes.length === 0) {
                return Swal.fire("Gagal", "Mindmap kosong. Tambahkan node terlebih dahulu.",
                    "error");
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
                        mindmap: nodes
                    })
                });

                if (!res.ok) {
                    const errText = await res.text();
                    throw new Error(errText);
                }

                const result = await res.json();

                Swal.fire({
                    title: 'Ringkasan Mindmap 📚',
                    html: `
                        <div style="text-align:left;">
                            <details open style="margin-bottom:10px;">
                                <summary><strong>🧠 Ringkasan Lengkap</strong></summary>
                                <div style="margin-top:8px;white-space:pre-wrap;">${result.summary}</div>
                            </details>
                            <p style="font-size:0.9em;color:#666;">Ringkasan dihasilkan otomatis menggunakan AI.</p>
                        </div>
                    `,
                    width: '65%',
                    showCloseButton: true,
                    confirmButtonText: 'Selesai',
                    customClass: {
                        popup: 'animated fadeInUp faster'
                    }
                });

            } catch (err) {
                console.error("Fetch error:", err);
                Swal.fire('Gagal', 'Terjadi kesalahan saat memanggil server.', 'error');
            }
        });
    });

    document.getElementById('btn-save-mindmap')?.addEventListener('click', async () => {
        const title = await Swal.fire({
            title: 'Judul Mindmap',
            input: 'text',
            inputPlaceholder: 'Masukkan judul mindmap',
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            inputValidator: (value) => {
                if (!value) return 'Judul wajib diisi';
            }
        });

        if (!title.isConfirmed) return;

        const urlParts = window.location.pathname.split('/');
        const type = urlParts[urlParts.length - 1]; // e.g. 'spider'

        // 🔧 Generate mindmap structure
        const mindmapData = [];

        cy.nodes().forEach(node => {
            const parentEdge = cy.edges(`[target = "${node.id()}"]`).first();
            const parentNodeId = parentEdge.length ? parentEdge.data('source') : null;
            const parentLabel = parentNodeId ? cy.getElementById(parentNodeId).data('label') : null;

            mindmapData.push({
                node: node.data('label'),
                parent_node: parentLabel
            });
        });

        // ✅ Tambahkan ini untuk membuat pngData
        const pngData = cy.png({
            scale: 2,
            full: true,
            bg: '#ffffff',
            padding: 100
        });

        try {
            Swal.fire({
                title: 'Menyimpan...',
                html: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const res = await fetch('/mindmap/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    title: title.value,
                    type: type,
                    mindmap: mindmapData,
                    image: pngData // ✅ sekarang pngData sudah tersedia
                })
            });

            if (!res.ok) {
                const errText = await res.text();
                throw new Error(errText);
            }

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Mindmap berhasil disimpan!',
                timer: 2000,
                showConfirmButton: false
            });

            const sidebar = document.querySelector('[x-data="mindmapSidebar"]');
            if (sidebar && sidebar.__x) {
                sidebar.__x.$data.mindmapTitle = '🧠 ' + title.value;
            }

        } catch (err) {
            console.error(err);
            Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan mindmap.', 'error');
        }
    });
</script>

