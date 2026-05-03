@extends('layouts.app', ['header' => 'Tambah QR Code Lokasi', 'subheader' => 'QR Code akan digunakan karyawan untuk presensi di lokasi ini.'])

@push('styles')
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
  <form action="{{ route('admin.barcodes.store') }}" method="POST">
    @csrf
    @include('admin.barcodes._form')

    <div class="d-flex gap-2 mt-3">
      <button class="btn btn-primary"><i class="bi bi-save me-2"></i>Simpan QR Code</button>
      <a href="{{ route('admin.barcodes') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
  </form>
@endsection
