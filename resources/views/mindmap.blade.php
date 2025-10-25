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
                                        <div class="tooltip" data-tip="Reflecting">
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

                                        <div class="tooltip" data-tip="Buat Ringkasan dari AI">
                                            <button type="button"
                                                onclick="generateRingkasan({{ $map->id }}, @js($map->ringkasan_pribadi ?? '') )"
                                                class="btn btn-sm btn-secondary flex items-center gap-1">
                                                <x-lucide-bot class="w-4 h-4" />
                                            </button>
                                        </div>
                                        <div class="tooltip" data-tip="Download Mindmap">
                                            <a href="{{ asset('storage/' . $map->gambar_mindmap) }}" download
                                                class="btn btn-sm btn-success flex items-center gap-1">
                                                <x-lucide-download class="w-4 h-4" />
                                            </a>
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
                                            <div class="tooltip" data-tip="Summarizing">
                                                <button type="button"
                                                    onclick="buatRingkasan({{ $map->id }}, @js($map->ringkasan_pribadi ?? '') )"
                                                    class="btn btn-sm btn-secondary flex items-center gap-1">
                                                    <x-lucide-pencil-line class="w-4 h-4" />
                                                </button>
                                            </div>
                                        @endif
                                        <div class="tooltip" data-tip="Reflecting">
                                            <button type="button" onclick="showRingkasan({{ $map->id }})"
                                                class="btn btn-sm btn-primary flex items-center gap-1">
                                                <x-lucide-list class="w-4 h-4" />
                                            </button>
                                        </div>
                                        <div class="tooltip" data-tip="Download Mindmap">
                                            <a href="{{ asset('storage/' . $map->gambar_mindmap) }}" download
                                                class="btn btn-sm btn-success flex items-center gap-1">
                                                <x-lucide-download class="w-4 h-4" />
                                            </a>
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
            title: `<span class="text-[#123f77] font-bold text-lg">${data.title}</span>`,
            html: `
        <div class="text-left space-y-3 text-sm">
            <div class="alert alert-info shadow-sm">
                <x-lucide-info class="w-4 h-4 mr-2" />
                Ini adalah detail mindmap yang tersimpan.
            </div>

            <div class="border rounded p-3 bg-gray-50">
                <p><span class="font-semibold text-[#123f77]">👤 Pembuat:</span> ${data.created_by}</p>
                <p><span class="font-semibold text-[#123f77]">🗂️ Tipe:</span> ${data.type ?? '-'}</p>
                <p><span class="font-semibold text-[#123f77]">🔗 Shareable:</span> ${data.shareable === 'yes' ? 'Ya' : 'Tidak'}</p>
                <p><span class="font-semibold text-[#123f77]">🌳 Node Root:</span> ${data.node}</p>
                <p><span class="font-semibold text-[#123f77]">📅 Tanggal Dibuat:</span> ${data.created_at}</p>
            </div>
        </div>
        `,
            width: 600,
            confirmButtonText: 'Tutup',
            customClass: {
                htmlContainer: 'text-left'
            }
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

    async function showRingkasan(mindmapId) {
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
                    ringkasan_lain,
                    komentar_lain
                } = data;

                let html = `
                <div class="text-left space-y-4">
                    <div>
                        <h2 class="text-xl font-bold text-[#123f77]">${mindmap.title}</h2>
                        <p class="text-sm text-gray-500">
                            <span class="badge badge-outline mr-2">${mindmap.type}</span>
                            oleh <span class="font-semibold">${mindmap.creator}</span>
                            pada <span class="italic">${mindmap.created_at}</span>
                        </p>
                    </div>
                `;

                if (ringkasan_pribadi) {
                    html += `
                    <div class="border rounded-lg p-4 bg-blue-50">
                        <p class="font-semibold mb-2 text-[#123f77]">Ringkasan Pribadi Anda</p>
                        <div class="prose text-gray-700">${ringkasan_pribadi}</div>
                    </div>
                `;
                }

                if (ringkasan_lain.length > 0) {
                    html += `
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <p class="font-semibold mb-2 text-[#123f77]">Ringkasan dari Pengguna Lain</p>
                        <div class="space-y-2">
                `;

                    ringkasan_lain.forEach(item => {
                        html += `
                        <div class="flex items-start gap-2">
                            <span class="badge badge-primary">${item.user}</span>
                            <div class="bg-white border rounded-md p-2 flex-1 text-gray-700">
                                ${item.ringkasan}
                            </div>
                        </div>
                    `;
                    });

                    html += `</div></div>`;
                }

                html += `
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <p class="font-semibold mb-2 text-[#123f77]">💬 Komentar dari Pengguna Lain</p>
                        <div class="space-y-2 max-h-40 overflow-y-auto">
                `;

                if (komentar_lain.length > 0) {
                    komentar_lain.forEach(item => {
                        let posisiChat = item.user == data.current_user_id ? 'chat-end' :
                            'chat-start';
                        let bubbleColor = item.user == data.current_user_id ? 'bg-green-100' :
                            'bg-blue-100';

                        html += `
        <div class="chat ${posisiChat}">
            <div class="chat-header font-semibold">${item.user}</div>
            <div class="chat-bubble ${bubbleColor} text-gray-800">${item.komentar}</div>
        </div>
        `;
                    });
                } else {
                    html += `<p class="text-gray-500 italic">Belum ada komentar.</p>`;
                }

                html += `
                        </div>
                    </div>

                    <div class="border rounded-lg p-4 bg-white">
                        <p class="font-semibold mb-2 text-[#123f77]">✏️ Berikan Komentar Anda</p>
                        <textarea id="user-comment" class="textarea textarea-bordered w-full" rows="3" placeholder="Tulis komentar..."></textarea>
                        <button id="submit-comment" class="btn btn-primary mt-2 w-full">Kirim Komentar</button>
                    </div>

                </div>
                `;

                Swal.fire({
                    title: 'Ringkasan & Komentar',
                    html: html,
                    width: 700,
                    showConfirmButton: false,
                    didOpen: () => {
                        document.getElementById('submit-comment').addEventListener('click',
                            async () => {
                                const comment = document.getElementById('user-comment')
                                    .value
                                    .trim();
                                if (!comment) {
                                    return Swal.fire('Oops', 'Komentar tidak boleh kosong.',
                                        'warning');
                                }

                                try {
                                    const res = await fetch(
                                    `/mindmap/${mindmapId}/comment`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector(
                                                    'meta[name="csrf-token"]')
                                                ?.content || ''
                                        },
                                        body: JSON.stringify({
                                            komentar: comment
                                        })
                                    });

                                    if (!res.ok) throw new Error('Gagal mengirim komentar');

                                    Swal.fire('Berhasil', 'Komentar Anda berhasil dikirim.',
                                        'success').then(() => {
                                        showRingkasan(mindmapId);
                                    });

                                } catch (err) {
                                    console.error(err);
                                    Swal.fire('Gagal',
                                        'Terjadi kesalahan saat mengirim komentar.',
                                        'error'
                                    );
                                }
                            });
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

    function generateRingkasan(id) {
        Swal.fire({
            title: 'Menghasilkan Ringkasan AI...',
            html: '<div class="text-gray-600">Sedang memproses mindmap, mohon tunggu...</div>',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch(`/mindmap/${id}/generate-summary`)
            .then(res => res.json())
            .then(data => {
                if (data.error) throw new Error(data.error);

                const lines = data.summary.split('\n').map(l => l.trim()).filter(Boolean);

                let topicUtama = '',
                    subtopik = '',
                    ringkasanUmum = '';
                lines.forEach(line => {
                    if (line.toLowerCase().startsWith('topik utama')) topicUtama = line.replace(
                        /^Topik utama:\s*/i, '');
                    else if (line.toLowerCase().startsWith('subtopik')) subtopik = line.replace(
                        /^Subtopik:\s*/i, '');
                    else if (line.toLowerCase().startsWith('ringkasan umum')) ringkasanUmum = line.replace(
                        /^Ringkasan umum:\s*/i, '');
                });

                Swal.fire({
                    title: '<span class="text-[#123f77] font-bold">💡 Hasil Ringkasan AI</span>',
                    html: `
                <div class="text-left space-y-4">
                    <div class="alert alert-info shadow-sm text-sm">
                        <x-lucide-info class="w-4 h-4 mr-2" />
                        Ini adalah ringkasan otomatis dari AI, gunakan sebagai referensi belajar.
                    </div>

                    <div class="border rounded p-3 bg-blue-50">
                        <h3 class="font-semibold text-[#123f77] mb-1">📝 Topik Utama</h3>
                        <p class="text-gray-800">${topicUtama || '<em>Tidak ditemukan</em>'}</p>
                    </div>

                    <div class="border rounded p-3 bg-gray-50">
                        <h3 class="font-semibold text-[#123f77] mb-1">📋 Subtopik</h3>
                        <p class="text-gray-800">${subtopik || '<em>Tidak ditemukan</em>'}</p>
                    </div>

                    <div class="border rounded p-3 bg-white">
                        <h3 class="font-semibold text-[#123f77] mb-1">📖 Ringkasan Umum</h3>
                        <p class="text-gray-800">${ringkasanUmum || '<em>Tidak ditemukan</em>'}</p>
                    </div>
                </div>
                `,
                    width: 750,
                    confirmButtonText: 'Selesai'
                });

            })
            .catch(err => {
                console.error(err);
                Swal.fire('Gagal', err.message || 'Terjadi kesalahan saat memproses.', 'error');
            });
    }
</script>
