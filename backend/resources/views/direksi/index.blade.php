@extends('layout.master')

@section('title', 'Manajemen Direksi')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Manajemen Direksi</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        @foreach($direksi as $d)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    @if($d->image)
                        <img src="{{ asset('storage/'.$d->image) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light text-center py-4 text-muted">
                            Tidak ada foto
                        </div>
                    @endif
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $d->position }}</h5>
                        <p class="card-text text-muted">{{ $d->name }}</p>
                        <a href="{{ route('direksi.edit', $d->id) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
