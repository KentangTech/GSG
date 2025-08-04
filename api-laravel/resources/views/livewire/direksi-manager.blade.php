<div wire:key="direksi-section">
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="position-fixed top-0 end-0 m-3" style="z-index: 1050;">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert" style="border-radius: 12px; padding: 0.75rem 1.25rem;">
                <i class="fas fa-check-circle me-2"></i>
                <span>{{ session('message') }}</span>
                <button type="button" class="btn-close" @click="show = false" aria-label="Close"></button>
            </div>
        @endif
    </div>

    @if (!$limitReached)
        <div class="mb-4 text-end">
            <button type="button" wire:click="openCreateModal" class="btn btn-success px-4 py-2" style="border-radius: 12px; font-weight: 500;">
                <i class="fas fa-plus-circle me-1"></i> Tambah Direktur
            </button>
        </div>
    @else
        <div class="alert alert-info d-flex align-items-center shadow-sm mb-4" role="alert" style="border-radius: 12px; background: #e3f2fd;">
            <i class="fas fa-info-circle fs-4 me-3 text-primary"></i>
            <div>
                <h6 class="mb-0">Maksimal 3 direktur telah tercapai</h6>
                <small>Anda tidak dapat menambah direktur baru.</small>
            </div>
        </div>
    @endif

    <div class="card card-modern shadow-sm border-0 mt-4" style="border-radius: 18px;">
        <div class="card-header bg-white py-3 px-4 border-0">
            <h5 class="mb-0 text-dark fw-semibold d-flex align-items-center">
                <i class="fas fa-users text-success me-2"></i>
                Daftar Direksi
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-4" wire:loading.remove>
                @forelse($direksi as $d)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                            <img src="{{ $d->gambar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($d->nama) . '&background=4361ee&color=fff' }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title mb-1">{{ $d->nama }}</h6>
                                <p class="text-muted small mb-3">
                                    <i class="fas fa-briefcase me-1"></i>
                                    {{ $d->posisi }}
                                </p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary flex-grow-1" wire:click="edit({{ $d->id }})">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger flex-grow-1" wire:click="$confirm('Yakin ingin menghapus {{ $d->nama }}?', 'delete', [{{ $d->id }}])">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">Belum ada direktur yang ditambahkan.</p>
                    </div>
                @endforelse
            </div>
            <div wire:loading class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Memuat...</span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true" x-data x-init="$wire.on('open-create-modal', () => { const modal = new bootstrap.Modal($refs.modal); modal.show(); })">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title">Tambah Direktur Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="store">
                        <div class="mb-3">
                            <label class="form-label text-dark fw-medium">Nama Direktur</label>
                            <input type="text" class="form-control form-control-lg" wire:model.defer="nama" placeholder="Masukkan nama lengkap" required style="border-radius: 12px;">
                            @error('nama') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark fw-medium">Posisi</label>
                            <select class="form-select form-select-lg" wire:model.defer="posisi" required style="border-radius: 12px;">
                                <option value="">Pilih Posisi</option>
                                <option value="Direktur Utama">Direktur Utama</option>
                                <option value="Direktur Operasional">Direktur Operasional</option>
                                <option value="Direktur Keuangan">Direktur Keuangan</option>
                            </select>
                            @error('posisi') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark fw-medium">Foto Direktur</label>
                            <div x-data="{ isOver: false, error: null }"
                                @dragover.prevent="isOver = true"
                                @dragleave="isOver = false"
                                @drop.prevent="isOver = false; $refs.file.files = $event.dataTransfer.files; $refs.file.dispatchEvent(new Event('change'))"
                                :class="{ 'bg-primary bg-opacity-10 border-primary': isOver, 'border-danger': error }"
                                class="border-dashed rounded p-4 text-center"
                                style="border: 2px dashed #dee2e6; cursor: pointer; background: #f8f9fc;"
                                wire:loading.class="opacity-50">
                                <p class="text-muted mb-1" x-show="!$wire.gambar && !error">Seret gambar ke sini atau klik tombol di bawah</p>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-1" @click="$refs.file.click()">Pilih File</button>
                                <br>
                                <small class="text-muted mt-2 d-block">Hanya: PNG, JPG, JPEG, SVG | Maks 2MB</small>
                                <input type="file" class="d-none" accept=".png,.jpg,.jpeg,.svg" x-ref="file" wire:model="gambar" @change="error = $event.target.validity.valid ? null : 'File tidak valid';">
                                <div x-show="error" class="text-danger small mt-1" x-text="error"></div>
                            </div>
                            @error('gambar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            @if ($gambar)
                                <div class="mt-3 text-center">
                                    @if (in_array($gambar->getClientOriginalExtension(), ['png','jpg','jpeg']))
                                        <img src="{{ $gambar->temporaryUrl() }}" class="rounded shadow-sm" style="max-height: 150px; object-fit: cover; border: 3px solid #e0e7ff;">
                                    @else
                                        <div style="max-height: 150px; max-width: 150px; border: 3px solid #e0e7ff; border-radius: 12px; overflow: hidden; display: inline-block;">
                                            {!! file_get_contents($gambar->getRealPath()) !!}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-4" wire:loading.attr="disabled">
                                <span wire:loading.remove>Simpan</span>
                                <span wire:loading>Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true" x-data x-init="$wire.on('open-edit-modal', () => { const modal = new bootstrap.Modal($refs.modal); modal.show(); })">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title">Edit Direktur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="update">
                        <div class="mb-3">
                            <label class="form-label text-dark fw-medium">Nama</label>
                            <input type="text" class="form-control form-control-lg" wire:model.defer="edit_nama" required>
                            @error('edit_nama') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark fw-medium">Posisi</label>
                            <select class="form-select form-select-lg" wire:model.defer="edit_posisi" required>
                                <option value="Direktur Utama">Direktur Utama</option>
                                <option value="Direktur Operasional">Direktur Operasional</option>
                                <option value="Direktur Keuangan">Direktur Keuangan</option>
                            </select>
                            @error('edit_posisi') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark fw-medium">Foto (Opsional)</label>
                            <div x-data="{ isOver: false, error: null }"
                                @dragover.prevent="isOver = true"
                                @dragleave="isOver = false"
                                @drop.prevent="isOver = false; $refs.file.files = $event.dataTransfer.files; $refs.file.dispatchEvent(new Event('change'))"
                                :class="{ 'bg-primary bg-opacity-10 border-primary': isOver, 'border-danger': error }"
                                class="border-dashed rounded p-4 text-center"
                                style="border: 2px dashed #dee2e6; cursor: pointer; background: #f8f9fc;"
                                wire:loading.class="opacity-50">
                                <p class="text-muted mb-1" x-show="!$wire.edit_gambar && !error">Seret gambar ke sini atau pilih file</p>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-1" @click="$refs.file.click()">Pilih File</button>
                                <br>
                                <small class="text-muted mt-2 d-block">Hanya: PNG, JPG, JPEG, SVG | Maks 2MB</small>
                                <input type="file" class="d-none" accept=".png,.jpg,.jpeg,.svg" x-ref="file" wire:model="edit_gambar" @change="error = $event.target.validity.valid ? null : 'File tidak valid';">
                                <div x-show="error" class="text-danger small mt-1" x-text="error"></div>
                            </div>
                            @error('edit_gambar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            @if ($edit_gambar)
                                <div class="mt-3 text-center">
                                    @if (in_array($edit_gambar->getClientOriginalExtension(), ['png','jpg','jpeg']))
                                        <img src="{{ $edit_gambar->temporaryUrl() }}" class="img-thumbnail" style="max-height: 150px; object-fit: cover;">
                                    @else
                                        <div style="max-height: 150px; max-width: 150px; border: 3px solid #e0e7ff; border-radius: 12px; overflow: hidden; display: inline-block;">
                                            {!! file_get_contents($edit_gambar->getRealPath()) !!}
                                        </div>
                                    @endif
                                </div>
                            @elseif($edit_id && $direksi->find($edit_id)?->gambar_url)
                                <div class="mt-3 text-center">
                                    <img src="{{ $direksi->find($edit_id)->gambar_url }}" class="img-thumbnail" style="max-height: 150px; object-fit: cover;">
                                </div>
                            @endif
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-4" wire:loading.attr="disabled">
                                <span wire:loading.remove>Update</span>
                                <span wire:loading>Memperbarui...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', () => {
            Livewire.on('closeModal', () => {
                ['#createModal', '#editModal'].forEach(selector => {
                    const el = document.querySelector(selector);
                    if (el) {
                        const modal = bootstrap.Modal.getInstance(el);
                        if (modal) modal.hide();
                    }
                });
            });
        });
    </script>
</div>
