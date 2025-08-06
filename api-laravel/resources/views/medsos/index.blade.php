@extends('components.app')

@section('title', 'Manajemen Media Sosial')
@section('page-title', 'Media Sosial')

@section('content')
    <div class="container-fluid py-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold text-dark">Media Sosial</h2>
                <p class="text-muted">Kelola akun media sosial perusahaan.</p>
            </div>
            <a href="{{ route('medsos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            @forelse($medsos as $m)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ $m->image_url }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $m->name }}">

                        <div class="card-body">
                            <h5 class="card-title">{{ $m->name }}</h5>

                            @php
                                $username = $m->username ? trim($m->username) : '';
                            @endphp

                            @if($username)
                                <p class="text-muted">@{{ $username }}</p>
                            @endif

                            <div class="d-flex gap-2 flex-wrap">
                                @foreach($m->platforms as $p)
                                    <a href="{{ $p->url }}" target="_blank" class="badge bg-primary px-3 py-2">
                                        {{ $p->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0 pt-0">
                            <div class="d-flex gap-2">
                                <a href="{{ route('medsos.edit', $m) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('medsos.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-hashtag fa-2x text-muted mb-3"></i>
                    <p class="text-muted fs-5">Belum ada data media sosial.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
