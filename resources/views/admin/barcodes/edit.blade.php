@extends('layouts.app', ['header' => 'Edit QR Code Lokasi'])

@push('styles')
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <form action="{{ route('admin.barcodes.update', $barcode->id) }}" method="POST">
        @csrf @method('PATCH')
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Nama QR Code</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $barcode->name) }}">
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Kode Unik (Value)</label>
            <input type="text" name="value" class="form-control" required value="{{ old('value', $barcode->value) }}">
          </div>

          <div class="col-md-4">
            <label class="form-label small fw-semibold">Radius Valid (m)</label>
            <input type="number" name="radius" class="form-control" required value="{{ old('radius', $barcode->radius) }}">
          </div>
          <div class="col-md-4">
            <label class="form-label small fw-semibold">Latitude</label>
            <input type="text" name="lat" id="lat" class="form-control" required value="{{ old('lat', $barcode->latitude) }}">
          </div>
          <div class="col-md-4">
            <label class="form-label small fw-semibold">Longitude</label>
            <input type="text" name="lng" id="lng" class="form-control" required value="{{ old('lng', $barcode->longitude) }}">
          </div>

          <div class="col-12">
            <label class="form-label small fw-semibold">Update Lokasi di Peta</label>
            <div id="map" style="height:360px;border-radius:.5rem"></div>
          </div>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button class="btn btn-primary"><i class="bi bi-save me-2"></i>Update</button>
          <a href="{{ route('admin.barcodes.download', $barcode->id) }}" class="btn btn-outline-primary">
            <i class="bi bi-download me-2"></i>Download QR
          </a>
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
      location: [Number({{ old('lat', $barcode->latitude) }}), Number({{ old('lng', $barcode->longitude) }})]
    });
  });
</script>
@endpush
