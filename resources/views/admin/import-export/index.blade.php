@extends('layouts.app', ['header' => 'Import / Export Data', 'subheader' => 'Kelola data dalam format Excel.'])

@section('content')
  <div class="row g-3">
    <div class="col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-people me-2 text-primary"></i>Data Karyawan</h6>

          <h6 class="small text-muted text-uppercase mt-3">Export</h6>
          <form method="GET" action="{{ route('admin.users.export') }}" class="mb-4">
            <div class="mb-2 small">
              @foreach (\App\Models\User::$roleLabels as $k => $v)
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $k }}" id="rk_{{ $k }}"
                         {{ $k === 'karyawan' ? 'checked' : '' }}>
                  <label class="form-check-label" for="rk_{{ $k }}">{{ $v }}</label>
                </div>
              @endforeach
            </div>
            <button class="btn btn-sm btn-primary"><i class="bi bi-download me-1"></i>Download Excel</button>
          </form>

          <h6 class="small text-muted text-uppercase">Import</h6>
          <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="input-group input-group-sm">
              <input type="file" name="file" class="form-control" accept=".csv,.xls,.xlsx,.ods" required>
              <button class="btn btn-success"><i class="bi bi-upload me-1"></i>Upload</button>
            </div>
            <small class="text-muted d-block mt-1">Format: csv / xls / xlsx</small>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-calendar-check me-2 text-primary"></i>Data Presensi</h6>

          <h6 class="small text-muted text-uppercase mt-3">Export</h6>
          <form method="GET" action="{{ route('admin.attendances.export') }}" class="mb-4">
            <div class="row g-2">
              <div class="col-6">
                <label class="form-label small">Tanggal</label>
                <input type="date" name="date" class="form-control form-control-sm">
              </div>
              <div class="col-6">
                <label class="form-label small">Bulan</label>
                <input type="month" name="month" class="form-control form-control-sm">
              </div>
            </div>
            <button class="btn btn-sm btn-primary mt-2"><i class="bi bi-download me-1"></i>Download Excel</button>
          </form>

          <h6 class="small text-muted text-uppercase">Import</h6>
          <form method="POST" action="{{ route('admin.attendances.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="input-group input-group-sm">
              <input type="file" name="file" class="form-control" accept=".csv,.xls,.xlsx,.ods" required>
              <button class="btn btn-success"><i class="bi bi-upload me-1"></i>Upload</button>
            </div>
            <small class="text-muted d-block mt-1">Format: csv / xls / xlsx</small>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
