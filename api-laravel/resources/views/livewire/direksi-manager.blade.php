<div wire:key="direksi-{{ now()->timestamp }}">
    <!-- Tampilkan alert -->
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 5000)"
         x-transition
         class="position-fixed top-0 end-0 m-3"
         style="z-index: 1050;">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm"
                 role="alert"
                 style="border-radius: 12px; padding: 0.75rem 1.25rem;">
                <i class="fas fa-check-circle me-2"></i>
                <span>{{ session('message') }}</span>
                <button type="button" class="btn-close" @click="show = false" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Form Tambah (Hanya muncul jika < 3) -->
    @if (!$limitReached)
        <div class="card card-modern shadow-sm border-0" style="border-radius: 18px; overflow: hidden;">
            <div class="card-header bg-white py-3 px-4 border-0">
                <h5 class="mb-0 text-dark fw-semibold d-flex align-items-center">
                    <i class="fas fa-plus-circle text-primary me-2"></i>
                    Tambah Direktur Baru
                </h5>
            </div>
            <div class="card-body p-4">
                <form wire:submit.prevent="store">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-medium">Nama Direktur</label>
                            <input type="text"
                                   class="form-control form-control-lg"
                                   wire:model="nama"
                                   placeholder="Masukkan nama lengkap"
                                   required
                                   style="border-radius: 12px;">
                            @error('nama') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-medium">Posisi</label>
                            <select class="form-select form-select-lg"
                                    wire:model="posisi"
                                    required
                                    style="border-radius: 12px;">
                                <option value="">Pilih Posisi</option>
                                <option value="Direktur Utama">Direktur Utama</option>
                                <option value="Direktur Operasional">Direktur Operasional</option>
                                <option value="Direktur Keuangan">Direktur Keuangan</option>
                            </select>
                            @error('posisi') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Upload Gambar -->
                    <div class="mt-4">
                        <label class="form-label text-dark fw-medium">Foto Direktur</label>
                        <div
                            wire:loading.class="bg-primary bg-opacity-10 border-primary"
                            class="border-dashed rounded p-4 text-center"
                            style="border: 2px dashed #dee2e6; cursor: pointer; background: #f8f9fc;"
                            x-data="{ isOver: false }"
                            x-on:dragover="isOver = true"
                            x-on:dragleave="isOver = false"
                            x-on:drop="isOver = false"
                            x-bind:class="{ 'bg-primary bg-opacity-10 border-primary': isOver }"
                            wire:target="gambar"
                            wire:loading.class="opacity-50"
                        >
                            <div wire:loading.remove>
                                <p class="text-muted mb-0" x-show="$wire.gambar === null">Klik atau seret gambar di sini</p>
                                <small class="text-muted">Maks 2MB, format: JPG, PNG</small>
                            </div>
                            <div wire:loading>
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Uploading...</span>
                                </div>
                            </div>
                            <input type="file" class="d-none" wire:model="gambar" accept="image/*">
                        </div>
                        @error('gambar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

                        <!-- Preview Gambar -->
                        @if ($gambar)
                            <div class="mt-3 text-center">
                                <img src="{{ $gambar->temporaryUrl() }}"
                                     class="rounded shadow-sm"
                                     style="max-height: 150px; object-fit: cover; border: 3px solid #e0e7ff;">
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-4 py-2">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-info d-flex align-items-center shadow-sm" role="alert"
             style="border-radius: 12px; background: #e3f2fd;">
            <i class="fas fa-info-circle fs-4 me-3 text-primary"></i>
            <div>
                <h6 class="mb-0">Maksimal 3 direktur telah tercapai</h6>
                <small>Anda tidak dapat menambah direktur baru.</small>
            </div>
        </div>
    @endif

    <!-- Daftar Direksi -->
    <div class="card card-modern shadow-sm border-0 mt-4" style="border-radius: 18px;">
        <div class="card-header bg-white py-3 px-4 border-0">
            <h5 class="mb-0 text-dark fw-semibold d-flex align-items-center">
                <i class="fas fa-users text-success me-2"></i>
                Daftar Direksi
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-4" wire:loading.remove>
                @foreach($direksi as $d)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                            <!-- Gambar dari database -->
                            <img src="{{ $d->gambar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($d->nama) . '&background=4361ee&color=fff' }}"
                                 class="card-img-top"
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title mb-1">{{ $d->nama }}</h6>
                                <p class="text-muted small mb-3">
                                    <i class="fas fa-briefcase me-1"></i>
                                    {{ $d->posisi }}
                                </p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary flex-grow-1"
                                            wire:click="edit({{ $d->id }})"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger flex-grow-1"
                                            wire:click="$confirm('Yakin hapus?', 'delete', [{{ $d->id }}])">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div wire:loading class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Memuat...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title">Edit Direktur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="update">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" wire:model="edit_nama" required>
                            @error('edit_nama') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Posisi</label>
                            <select class="form-select" wire:model="edit_posisi" required>
                                <option value="Direktur Utama">Direktur Utama</option>
                                <option value="Direktur Operasional">Direktur Operasional</option>
                                <option value="Direktur Keuangan">Direktur Keuangan</option>
                            </select>
                            @error('edit_posisi') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto (Opsional)</label>
                            <input type="file" class="form-control" wire:model="edit_gambar">
                            @error('edit_gambar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

                            @if ($edit_gambar)
                                <div class="mt-3 text-center">
                                    <img src="{{ $edit_gambar->temporaryUrl() }}" class="img-thumbnail" width="150">
                                </div>
                            @elseif($d = \App\Models\Direksi::find($edit_id))
                                <div class="mt-3 text-center">
                                    <img src="{{ $d->gambar_url }}" class="img-thumbnail" width="150">
                                </div>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('close-modal', () => {
                var modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                if (modal) modal.hide();
            });
        </script>
    </div>
</div>