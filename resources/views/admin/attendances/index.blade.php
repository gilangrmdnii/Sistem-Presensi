@extends('layouts.app', ['header' => 'Data Presensi Karyawan', 'subheader' => 'Pantau kehadiran karyawan secara real-time.'])

@section('content')
  <div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Tanggal</label>
          <input type="date" name="date" class="form-control form-control-sm" value="{{ $date }}">
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Divisi</label>
          <select name="division" class="form-select form-select-sm">
            <option value="">Semua</option>
            @foreach ($divisions as $d)
              <option value="{{ $d->id }}" {{ $divisionId==$d->id ? 'selected':'' }}>{{ $d->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
          <button class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i>Tampilkan</button>
          <a href="{{ route('admin.attendances') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
          <a href="{{ route('admin.attendances.export') }}?date={{ $date }}" class="btn btn-sm btn-success ms-auto">
            <i class="bi bi-filetype-xlsx me-1"></i>Excel
          </a>
        </div>
      </form>
    </div>
  </div>

  @php
    $present = $employees->filter(fn($e) => $e->today_attendance && in_array($e->today_attendance->status, ['present','late']))->count();
    $absent = $employees->filter(fn($e) => !$e->today_attendance)->count();
    $leave = $employees->filter(fn($e) => $e->today_attendance && in_array($e->today_attendance->status, ['excused','sick']))->count();
  @endphp

  <div class="row g-3 mb-3">
    <div class="col-md-3">
      <div class="stat-card primary">
        <div class="stat-icon"><i class="bi bi-people"></i></div>
        <div class="stat-value">{{ $employees->count() }}</div>
        <div class="stat-label">Total Karyawan</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card success">
        <div class="stat-icon"><i class="bi bi-person-check"></i></div>
        <div class="stat-value">{{ $present }}</div>
        <div class="stat-label">Hadir</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card info">
        <div class="stat-icon"><i class="bi bi-envelope-paper"></i></div>
        <div class="stat-value">{{ $leave }}</div>
        <div class="stat-label">Izin / Sakit</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card danger">
        <div class="stat-icon"><i class="bi bi-person-x"></i></div>
        <div class="stat-value">{{ $absent }}</div>
        <div class="stat-label">Tidak Hadir</div>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body pb-0 d-flex justify-content-between">
      <h6 class="fw-bold mb-0">Presensi &mdash; {{ \Illuminate\Support\Carbon::parse($date)->translatedFormat('l, d F Y') }}</h6>
      <span class="text-muted small">{{ $employees->count() }} karyawan</span>
    </div>
    <div class="table-responsive mt-3">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th class="ps-3">Karyawan</th>
            <th>Divisi</th>
            <th>Jam Masuk</th>
            <th>Jam Pulang</th>
            <th>Status</th>
            <th class="pe-3">Lokasi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($employees as $e)
            @php($a = $e->today_attendance)
            <tr>
              <td class="ps-3">
                <div class="d-flex align-items-center gap-2">
                  <img src="{{ $e->profile_photo_url }}" width="32" height="32" class="rounded-circle" style="object-fit:cover">
                  <div>
                    <div class="fw-semibold">{{ $e->name }}</div>
                    <div class="small text-muted">{{ $e->nip ?? '—' }}</div>
                  </div>
                </div>
              </td>
              <td class="small">{{ $e->division?->name ?? '—' }}</td>
              <td>{{ $a?->time_in ?? '—' }}</td>
              <td>{{ $a?->time_out ?? '—' }}</td>
              <td>
                @if ($a)
                  <span class="badge-soft badge-{{ $a->status }}">{{ ucfirst($a->status) }}</span>
                @else
                  <span class="badge-soft badge-absent">Belum Absen</span>
                @endif
              </td>
              <td class="pe-3 small">
                @if ($a?->latitude && $a?->longitude)
                  <a href="https://maps.google.com/?q={{ $a->latitude }},{{ $a->longitude }}" target="_blank"
                     class="text-decoration-none">
                    <i class="bi bi-geo-alt"></i> Lihat peta
                  </a>
                @else
                  —
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
