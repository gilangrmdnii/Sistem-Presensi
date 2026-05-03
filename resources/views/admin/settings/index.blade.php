@extends('layouts.app', ['header' => 'Pengaturan Sistem', 'subheader' => 'Konfigurasi jam kerja dan lokasi kantor.'])

@section('content')
  <form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')

    <div class="row g-3">
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <h6 class="fw-bold mb-1"><i class="bi bi-clock me-2 text-primary"></i>Jam Kerja</h6>
            <p class="text-muted small mb-3">Acuan untuk menentukan status hadir / terlambat saat presensi.</p>

            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label small fw-semibold">Jam Masuk *</label>
                <input type="time" name="work_start" class="form-control" required
                       value="{{ old('work_start', $workStart) }}">
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-semibold">Jam Pulang *</label>
                <input type="time" name="work_end" class="form-control" required
                       value="{{ old('work_end', $workEnd) }}">
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-semibold">Toleransi Terlambat (menit) *</label>
                <input type="number" name="late_tolerance" class="form-control" required min="0" max="120"
                       value="{{ old('late_tolerance', $lateTolerance) }}">
              </div>
              <div class="col-12">
                <div class="alert alert-info small mb-0">
                  <i class="bi bi-info-circle me-1"></i>
                  Karyawan yang presensi setelah <strong>{{ $workStart }}</strong> +
                  <strong>{{ $lateTolerance }} menit</strong> akan otomatis berstatus <em>Terlambat</em>.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <h6 class="fw-bold mb-1"><i class="bi bi-geo-alt me-2 text-primary"></i>Lokasi Kantor (GPS)</h6>
            <p class="text-muted small mb-3">Koordinat default & radius validasi presensi (jika QR Code tidak punya lokasi).</p>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Latitude</label>
                <input type="text" name="office_latitude" class="form-control" value="{{ old('office_latitude', $officeLat) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Longitude</label>
                <input type="text" name="office_longitude" class="form-control" value="{{ old('office_longitude', $officeLng) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Radius Valid (meter)</label>
                <input type="number" name="office_radius" class="form-control" min="10" max="5000" value="{{ old('office_radius', $officeRadius) }}">
              </div>
              <div class="col-md-6 d-flex align-items-end">
                <a href="https://maps.google.com/?q={{ $officeLat }},{{ $officeLng }}" target="_blank" class="btn btn-sm btn-outline-secondary w-100">
                  <i class="bi bi-map me-1"></i>Lihat di Peta
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h6 class="fw-bold mb-1"><i class="bi bi-building me-2 text-primary"></i>Identitas Perusahaan</h6>
            <p class="text-muted small mb-3">Nama yang ditampilkan pada laporan PDF & header email.</p>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama Perusahaan *</label>
                <input type="text" name="company_name" class="form-control" required value="{{ old('company_name', $companyName) }}">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex gap-2 mt-3">
      <button class="btn btn-primary"><i class="bi bi-save me-2"></i>Simpan Pengaturan</button>
    </div>
  </form>
@endsection
