@extends('layouts.app', ['header' => 'Edit QR Code: '.$barcode->name])

@push('styles')
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
  <form action="{{ route('admin.barcodes.update', $barcode->id) }}" method="POST">
    @csrf @method('PATCH')
    @include('admin.barcodes._form')

    <div class="d-flex gap-2 mt-3">
      <button class="btn btn-primary"><i class="bi bi-save me-2"></i>Update QR Code</button>
      <a href="{{ route('admin.barcodes.download', $barcode->id) }}" class="btn btn-outline-primary">
        <i class="bi bi-download me-2"></i>Download QR
      </a>
      <a href="{{ route('admin.barcodes') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
  </form>
@endsection
