<x-main title="Mindmap Save" class="p-0 min-h-screen flex flex-col">
    <x-home.navbar />

    <section class="flex-grow bg-gradient-to-br from-[#f0f6ff] to-[#e0ebff] px-6 py-12">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-10">
                <h1 class="text-4xl font-extrabold tracking-tight text-[#123f77] capitalize">Mindmap Tersimpan</h1>
            </div>

            @if ($mindmaps->isEmpty())
                <div class="text-center text-gray-500">
                    <p>Belum ada mindmap yang tersimpan.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach ($mindmaps as $map)
                        <div
                            class="card bg-white border rounded-xl shadow-sm hover:shadow-lg transition flex flex-col h-full">
                            @if ($map->gambar_mindmap)
                                <img src="{{ asset('storage/' . $map->gambar_mindmap) }}" alt="Mindmap"
                                    onclick="showImage('{{ asset('storage/' . $map->gambar_mindmap) }}')"
                                    class="cursor-pointer rounded-t-xl h-48 w-full object-contain bg-white p-2" />
                            @else
                                <div
                                    class="h-48 flex items-center justify-center bg-gray-100 text-gray-400 rounded-t-xl">
                                    <x-lucide-image-off class="w-10 h-10" />
                                    <span class="ml-2 text-sm">Tidak ada gambar</span>
                                </div>
                            @endif

                            <div class="p-4 flex-grow flex flex-col justify-between space-y-3">
                                <div>
                                    <h2 class="text-[#123f77] font-semibold text-lg mb-1 capitalize">{{ $map->title }}
                                    </h2>
                                    <p class="text-sm text-gray-600">Jenis: <span
                                            class="capitalize">{{ $map->type ?? '-' }}</span></p>
                                </div>

                                @if (Auth::user()->role === 'admin')
                                    <div class="flex flex-wrap justify-end gap-2 border-t pt-4">
                                        <!-- Tombol Ringkasan -->
                                        <div class="tooltip" data-tip="Lihat Ringkasan">
                                            <button type="button" onclick="showRingkasan({{ $map->id }})"
                                                class="btn btn-sm btn-primary flex items-center gap-1">
                                                <x-lucide-list class="w-4 h-4" />
                                            </button>
                                        </div>

                                        <!-- Tombol Share -->
                                        <div class="tooltip"
                                            data-tip="{{ $map->shareable === 'yes' ? 'Batalkan Share' : 'Bagikan Mindmap' }}">
                                            <button type="button" onclick="toggleShare({{ $map->id }}, this)"
                                                class="btn btn-sm {{ $map->shareable === 'yes' ? 'btn-success' : 'btn-warning' }} flex items-center gap-1">
                                                <x-lucide-share-2 class="w-4 h-4" />
                                            </button>
                                        </div>

                                        <!-- Tombol Detail -->
                                        <div class="tooltip" data-tip="Lihat Detail Mindmap">
                                            <button type="button"
                                                onclick="lihatDetail({
                                                    title: @js($map->title),
                                                    type: @js($map->type),
                                                    shareable: @js($map->shareable),
                                                    node: @js($map->node),
                                                    ringkasan: @js($map->ringkasan_pribadi),
                                                    created_by: @js(optional($map->userRelation)->name ?? 'Tidak diketahui'),
                                                    created_at: @js($map->created_at->format('d M Y H:i'))
                                                })"
                                                class="btn btn-sm btn-info flex items-center gap-1">
                                                <x-lucide-eye class="w-4 h-4" />
                                            </button>
                                        </div>

                                        <!-- Tombol Hapus -->
                                        <div class="tooltip" data-tip="Hapus Mindmap">
                                            <button type="button"
                                                onclick="hapusMindmap('{{ route('mindmap.destroy', $map->id) }}')"
                                                class="btn btn-sm btn-error flex items-center gap-1">
                                                <x-lucide-trash-2 class="w-4 h-4" />
                                            </button>
                                        </div>

                                        <div class="tooltip" data-tip="Buat Ringkasan">
                                            <button type="button"
                                                onclick="buatRingkasan({{ $map->id }}, @js($map->ringkasan_pribadi ?? '') )"
                                                class="btn btn-sm btn-secondary flex items-center gap-1">
                                                <x-lucide-pencil-line class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex flex-wrap justify-end gap-2 border-t pt-4">
                                        @if ($map->user === Auth::id() || $map->shareable !== 'yes')
                                            <div class="tooltip" data-tip="Hapus Mindmap">
                                                <button type="button"
                                                    onclick="hapusMindmap('{{ route('mindmap.destroy', $map->id) }}')"
                                                    class="btn btn-sm btn-error flex items-center gap-1">
                                                    <x-lucide-trash-2 class="w-4 h-4" />
                                                </button>
                                            </div>
                                        @endif
                                        @if (!in_array($map->id, $ringkasanIds ?? []))
                                            <div class="tooltip" data-tip="Buat Ringkasan">
                                                <button type="button"
                                                    onclick="buatRingkasan({{ $map->id }}, @js($map->ringkasan_pribadi ?? '') )"
                                                    class="btn btn-sm btn-secondary flex items-center gap-1">
                                                    <x-lucide-pencil-line class="w-4 h-4" />
                                                </button>
                                            </div>
                                        @endif
                                        <div class="tooltip" data-tip="Lihat Ringkasan">
                                            <button type="button" onclick="showRingkasan({{ $map->id }})"
                                                class="btn btn-sm btn-primary flex items-center gap-1">
                                                <x-lucide-list class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-main>
<script>
    function buatRingkasan(id, existingContent = '') {
        Swal.fire({
            title: 'Buat Ringkasan',
            html: `
            <div id="quill-editor-container">
                <div id="quill-editor" style="height: 200px;"></div>
                <textarea id="quill-content" hidden name="ringkasan"></textarea>
            </div>
        `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            focusConfirm: false,
            width: 800,
            didOpen: () => {
                const quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    placeholder: 'Tulis ringkasan di sini...',
                });

                if (existingContent) {
                    quill.root.innerHTML = existingContent;
                }

                Swal.getConfirmButton().addEventListener('click', function() {
                    const htmlContent = quill.root.innerHTML;

                    // Kirim melalui AJAX ke Laravel
                    fetch(`/mindmap/${id}/ringkasan`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ringkasan: htmlContent
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Tersimpan!', data.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Gagal', data.message || 'Terjadi kesalahan.',
                                    'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'Tidak dapat terhubung ke server.', 'error');
                        });
                });
            }
        });
    }

    function lihatDetail(data) {
        Swal.fire({
            title: data.title,
            html: `
                <div class="text-left text-sm">
                    <p><strong>Pembuat:</strong> ${data.created_by}</p>
                    <p><strong>Tipe:</strong> ${data.type ?? '-'}</p>
                    <p><strong>Shareable:</strong> ${data.shareable === 'yes' ? 'Ya' : 'Tidak'}</p>
                    <p><strong>Node Root:</strong> ${data.node}</p>
                    <p><strong>Tanggal dibuat:</strong> ${data.created_at}</p>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Tutup'
        });
    }

    function toggleShare(id, button) {
        Swal.fire({
            title: 'Ubah status berbagi?',
            text: 'Apakah Anda ingin mengubah status shareable mindmap ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ubah',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/mindmaps/${id}/toggle-share`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.shareable) {
                            Swal.fire('Berhasil!', data.message, 'success');

                            // Update tooltip & button class
                            const tooltip = button.closest('.tooltip');
                            tooltip.setAttribute('data-tip', data.shareable === 'yes' ? 'Batalkan Share' :
                                'Bagikan Mindmap');

                            // Update warna tombol
                            button.classList.remove('btn-success', 'btn-warning');
                            button.classList.add(data.shareable === 'yes' ? 'btn-success' : 'btn-warning');
                        } else {
                            Swal.fire('Gagal', data.message || 'Gagal mengubah status.', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Gagal', 'Terjadi kesalahan pada server.', 'error');
                    });
            }
        });
    }

    function hapusMindmap(url) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Mindmap yang dihapus tidak dapat dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                `;

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function showRingkasan(mindmapId) {
        Swal.fire({
            title: 'Mengambil ringkasan...',
            text: 'Harap tunggu sebentar.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`/mindmap/${mindmapId}/ringkasan`)
            .then(response => response.json())
            .then(data => {
                const {
                    mindmap,
                    ringkasan_pribadi,
                    ringkasan_lain
                } = data;

                let html = `
                <div class="text-left">
                    <h2 class="font-semibold mb-2">${mindmap.title}</h2>
                    <p class="text-sm text-gray-500 mb-4">${mindmap.type} oleh ${mindmap.creator} pada ${mindmap.created_at}</p>
            `;

                if (ringkasan_pribadi) {
                    html += `
                    <p class="font-semibold mb-2">Ringkasan Pribadi Anda:</p>
                    <div class="bg-gray-100 p-4 rounded-md mb-4">
                        ${ringkasan_pribadi}
                    </div>
                `;
                }

                if (ringkasan_lain.length > 0) {
                    html += `<p class="font-semibold mb-2">Ringkasan dari Pengguna Lain:</p>`;

                    ringkasan_lain.forEach(item => {
                        html += `
                        <div class="flex items-baseline gap-2 mb-2">
                            <strong class="w-20">${item.user}:</strong>
                            <div class="bg-gray-100 p-2 rounded-md flex-1">
                                ${item.ringkasan}
                            </div>
                        </div>
                    `;
                    });
                } else {
                    html += `<p class="text-sm text-gray-500 italic">Tidak ada ringkasan lain.</p>`;
                }

                html += `</div>`;

                Swal.fire({
                    title: 'Ringkasan',
                    html: html,
                    width: 700,
                    icon: 'info',
                    customClass: {
                        htmlContainer: 'text-left'
                    }
                });
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Gagal', 'Gagal mengambil data ringkasan.', 'error');
            });
    }

    function showImage(url) {
        Swal.fire({
            title: 'Pratinjau Gambar',
            imageUrl: url,
            imageAlt: 'Gambar Mindmap',
            showCloseButton: true,
            showConfirmButton: false,
            background: '#fff',
            width: 'auto',
            customClass: {
                popup: 'rounded-xl'
            }
        });
    }
</script>
