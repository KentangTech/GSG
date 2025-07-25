@extends('layout.master')

@section('title', 'Edit Direktur')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Edit Direktur</h2>

    <form method="POST" action="{{ route('direksi.update', $direksi->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Jabatan</label>
            <input type="text" name="position" class="form-control" value="{{ old('position', $direksi->position) }}" required>
        </div>

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $direksi->name) }}" required>
        </div>

        <div class="mb-3">
            <label>Foto</label>
            <input type="file" name="image" class="form-control">
        </div>

        @if($direksi->image)
            <div class="mb-3">
                <img src="{{ asset('storage/'.$direksi->image) }}" width="100" class="img-thumbnail mb-3">
            </div>
        @endif

        <a href="{{ route('direksi.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-success">Perbarui</button>
    </form>
</div>
@endsection
