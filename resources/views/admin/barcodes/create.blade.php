@extends('layouts.app', ['header' => 'Tambah QR Code Lokasi'])

@push('styles')
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <form action="{{ route('admin.barcodes.store') }}" method="POST">
        @csrf
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Nama QR Code</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="QR Code Kantor Pusat">
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Kode Unik (Value)</label>
            <input type="text" name="value" class="form-control" required value="{{ old('value', \Illuminate\Support\Str::random(16)) }}">
            <small class="text-muted">Nilai unik yang akan di-encode ke QR Code.</small>
          </div>

          <div class="col-md-4">
            <label class="form-label small fw-semibold">Radius Valid (m)</label>
            <input type="number" name="radius" class="form-control" required value="{{ old('radius', 50) }}">
          </div>
          <div class="col-md-4">
            <label class="form-label small fw-semibold">Latitude</label>
            <input type="text" name="lat" id="lat" class="form-control" required value="{{ old('lat') }}">
          </div>
          <div class="col-md-4">
            <label class="form-label small fw-semibold">Longitude</label>
            <input type="text" name="lng" id="lng" class="form-control" required value="{{ old('lng') }}">
          </div>

          <div class="col-12">
            <label class="form-label small fw-semibold">Pilih Lokasi di Peta</label>
            <div id="map" style="height:360px;border-radius:.5rem"></div>
          </div>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button class="btn btn-primary"><i class="bi bi-save me-2"></i>Simpan</button>
          <a href="{{ route('admin.barcodes') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  window.addEventListener('load', () => {
    window.initializeMap({
      onUpdate: (lat, lng) => {
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
      },
      location: @if (old('lat') && old('lng')) [Number({{ old('lat') }}), Number({{ old('lng') }})] @else undefined @endif
    });
  });
</script>
@endpush
