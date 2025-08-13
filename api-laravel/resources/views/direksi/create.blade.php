@extends('components.app')

@php
    $isEdit = isset($direksi) && $direksi instanceof \App\Models\Direksi;
    $pageTitle = $isEdit ? 'Edit Direksi' : 'Tambah Direksi';
    $buttonText = $isEdit ? 'Update' : 'Simpan';
@endphp

@section('title', $pageTitle)
@section('page-title', $pageTitle)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <!-- Card -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">

                    <!-- Header -->
                    <div class="text-center mb-5">
                        <h2 class="fw-bold text-dark mb-1">{{ $pageTitle }}</h2>
                        <p class="text-muted mb-0">Isi data lengkap dan unggah foto jika diperlukan.</p>
                    </div>

                    <!-- Error Alert -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Form -->
                    <form action="{{ $isEdit ? route('direksi.update', $direksi->id) : route('direksi.store') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <!-- Nama -->
                        <div class="mb-4">
                            <label for="nama" class="form-label fw-medium">Nama Lengkap</label>
                            <input type="text"
                                   name="nama"
                                   id="nama"
                                   class="form-control form-control-lg @error('nama') is-invalid @enderror"
                                   value="{{ old('nama', $isEdit ? $direksi->nama : '') }}"
                                   placeholder="Masukkan nama lengkap"
                                   required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Posisi (Dropdown) -->
                        <div class="mb-4">
                            <label for="posisi" class="form-label fw-medium">Posisi / Jabatan</label>
                            <select name="posisi" id="posisi"
                                    class="form-control form-control-lg @error('posisi') is-invalid @enderror"
                                    required>
                                <option value="" disabled {{ old('posisi', $isEdit ? $direksi->posisi : '') ? '' : 'selected' }}>
                                    -- Pilih Posisi --
                                </option>
                                <option value="Direktur Utama" {{ old('posisi', $isEdit ? $direksi->posisi : '') == 'Direktur Utama' ? 'selected' : '' }}>
                                    Direktur Utama
                                </option>
                                <option value="Direktur Keuangan" {{ old('posisi', $isEdit ? $direksi->posisi : '') == 'Direktur Keuangan' ? 'selected' : '' }}>
                                    Direktur Keuangan
                                </option>
                                <option value="Direktur Operasional" {{ old('posisi', $isEdit ? $direksi->posisi : '') == 'Direktur Operasional' ? 'selected' : '' }}>
                                    Direktur Operasional
                                </option>
                            </select>
                            @error('posisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload Foto -->
                        <div class="mb-4">
                            <label for="foto" class="form-label fw-medium">Unggah Foto (Opsional)</label>

                            <!-- Hidden File Input -->
                            <input type="file"
                                   name="foto"
                                   id="foto"
                                   class="d-none"
                                   accept="image/*">

                            <!-- Drag & Drop Zone -->
                            <div id="dropzone"
                                 class="border border-dashed border-3 rounded-4 text-center py-5 px-4"
                                 style="cursor: pointer; transition: all 0.3s;"
                                 ondragover="dragOver(event)"
                                 ondragleave="dragLeave(event)"
                                 ondrop="dropFile(event)">
                                <i class="fas fa-cloud-upload-alt text-primary" style="font-size: 3rem;"></i>
                                <p class="mt-3 mb-0 text-muted">
                                    Seret & lepas file di sini, atau <strong>klik untuk memilih</strong>
                                </p>
                                <small class="text-muted">Maksimal 15MB, format: JPG, PNG, WEBP</small>
                            </div>

                            @error('foto')
                                <div class="invalid-feedback d-block mt-2 text-center">{{ $message }}</div>
                            @enderror

                            <!-- Preview Gambar -->
                            <div class="mt-4 text-center">
                                <label class="d-block fw-medium">Pratinjau Foto</label>
                                <div class="d-inline-block" style="width: 150px; height: 150px;">
                                    <img id="image-preview"
                                         class="rounded-3 border shadow-sm w-100 h-100"
                                         style="object-fit: cover;"
                                         src="{{
                                             $isEdit && $direksi->foto
                                                 ? asset('storage/' . $direksi->foto)
                                                 : 'https://ui-avatars.com/api/?name=' .
                                                   urlencode(old('nama', $isEdit ? $direksi->nama : 'Direksi')) .
                                                   '&background=4361ee&color=fff&size=150'
                                         }}"
                                         alt="Pratinjau Foto">
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-grid gap-3 d-sm-flex justify-content-center mt-5">
                            <button type="submit"
                                    class="btn btn-success px-4 py-2 fs-5 d-flex align-items-center gap-2 shadow-sm">
                                <i class="fas fa-save"></i> {{ $buttonText }}
                            </button>
                            <a href="{{ route('direksi.index') }}"
                               class="btn btn-secondary px-4 py-2 d-flex align-items-center gap-2">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HAPUS: Cropper.js CSS dan Modal -->
<!-- Tidak ada modal crop dan tidak ada library cropper -->

<!-- JavaScript Sederhana untuk Upload & Preview -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('foto');
        const preview = document.getElementById('image-preview');

        // Klik dropzone → buka file picker
        dropzone.addEventListener('click', () => fileInput.click());

        // Handle file change (langsung preview)
        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Drag over
        function dragOver(e) {
            e.preventDefault();
            dropzone.classList.add('border-primary', 'bg-light');
            dropzone.style.backgroundColor = 'rgba(67, 97, 238, 0.05)';
        }

        // Drag leave
        function dragLeave(e) {
            e.preventDefault();
            dropzone.classList.remove('border-primary', 'bg-light');
            dropzone.style.backgroundColor = '';
        }

        // Drop file
        function dropFile(e) {
            e.preventDefault();
            dragLeave(e);
            const file = e.dataTransfer.files[0];
            if (file && file.type.match('image.*')) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
                fileInput.dispatchEvent(new Event('change'));
            } else {
                alert('Hanya file gambar yang diperbolehkan!');
            }
        }
    });
</script>

@endsection
