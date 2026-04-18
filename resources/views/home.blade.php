@extends('layouts.app', ['header' => 'Selamat datang, '.auth()->user()->name, 'subheader' => 'Ringkasan aktivitas presensi Anda hari ini.'])

@section('content')
  @php
    $present = $monthlyStats['present'] ?? 0;
    $late = $monthlyStats['late'] ?? 0;
    $excused = ($monthlyStats['excused'] ?? 0) + ($monthlyStats['sick'] ?? 0);
    $absent = $monthlyStats['absent'] ?? 0;
  @endphp

  <div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
      <div class="stat-card primary">
        <div class="stat-icon"><i class="bi bi-calendar-check"></i></div>
        <div class="stat-value">{{ $present }}</div>
        <div class="stat-label">Hadir bulan ini</div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="stat-card warning">
        <div class="stat-icon"><i class="bi bi-clock"></i></div>
        <div class="stat-value">{{ $late }}</div>
        <div class="stat-label">Terlambat bulan ini</div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="stat-card info">
        <div class="stat-icon"><i class="bi bi-envelope-paper"></i></div>
        <div class="stat-value">{{ $excused }}</div>
        <div class="stat-label">Izin / sakit bulan ini</div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="stat-card danger">
        <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
        <div class="stat-value">{{ $pendingLeaves }}</div>
        <div class="stat-label">Izin menunggu persetujuan</div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-lg-5">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-calendar3 me-2 text-primary"></i>Presensi Hari Ini</h6>

          @if ($todayAttendance)
            <div class="mb-2">
              <span class="badge-soft badge-{{ $todayAttendance->status }}">{{ ucfirst($todayAttendance->status) }}</span>
            </div>
            <dl class="row small mb-0">
              <dt class="col-5 text-muted">Tanggal</dt>
              <dd class="col-7">{{ \Illuminate\Support\Carbon::parse($todayAttendance->date)->translatedFormat('d F Y') }}</dd>
              <dt class="col-5 text-muted">Jam Masuk</dt>
              <dd class="col-7">{{ $todayAttendance->time_in ?? '—' }}</dd>
              <dt class="col-5 text-muted">Jam Pulang</dt>
              <dd class="col-7">{{ $todayAttendance->time_out ?? '—' }}</dd>
            </dl>
          @else
            <p class="text-muted small mb-3">Anda belum melakukan presensi hari ini.</p>
          @endif

          <div class="d-grid gap-2 mt-3">
            <a href="{{ route('attendance.scan') }}" class="btn btn-primary">
              <i class="bi bi-qr-code-scan me-2"></i>Scan QR Code
            </a>
            <a href="{{ route('leave-requests.create') }}" class="btn btn-outline-secondary">
              <i class="bi bi-envelope-paper me-2"></i>Ajukan Izin
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat 7 Hari Terakhir</h6>

          @if ($recentAttendances->isEmpty())
            <p class="text-muted small mb-0">Belum ada riwayat presensi.</p>
          @else
            <div class="table-responsive">
              <table class="table table-sm align-middle mb-0">
                <thead><tr><th>Tanggal</th><th>Masuk</th><th>Pulang</th><th>Status</th></tr></thead>
                <tbody>
                  @foreach ($recentAttendances as $a)
                    <tr>
                      <td>{{ \Illuminate\Support\Carbon::parse($a->date)->translatedFormat('d M Y') }}</td>
                      <td>{{ $a->time_in ?? '—' }}</td>
                      <td>{{ $a->time_out ?? '—' }}</td>
                      <td><span class="badge-soft badge-{{ $a->status }}">{{ ucfirst($a->status) }}</span></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
