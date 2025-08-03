@extends('components.app')

@section('title', 'Kelola Direksi')
@section('page-title', 'Manajemen Direksi')

@section('content')
    @livewire('direksi-manager')
    <div wire:key="direksi-manager-{{ now()->timestamp }}">
    @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @endpush
