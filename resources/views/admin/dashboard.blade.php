@extends('layouts.app', ['header' => 'Dashboard HRD', 'subheader' => 'Ringkasan kehadiran & pengajuan izin hari ini.'])

@section('content')
  <div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
      <div class="stat-card primary">
        <div class="stat-icon"><i class="bi bi-people"></i></div>
        <div class="stat-value">{{ $totalKaryawan }}</div>
        <div class="stat-label">Total Karyawan</div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="stat-card success">
        <div class="stat-icon"><i class="bi bi-person-check"></i></div>
        <div class="stat-value">{{ $presentCount }}</div>
        <div class="stat-label">Hadir Hari Ini</div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="stat-card warning">
        <div class="stat-icon"><i class="bi bi-clock"></i></div>
        <div class="stat-value">{{ $lateCount }}</div>
        <div class="stat-label">Terlambat Hari Ini</div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="stat-card danger">
        <div class="stat-icon"><i class="bi bi-person-x"></i></div>
        <div class="stat-value">{{ $absentCount }}</div>
        <div class="stat-label">Tidak Hadir</div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-lg-7">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-graph-up me-2 text-primary"></i>Tren Kehadiran 7 Hari</h6>
          <canvas id="trendChart" height="100"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-envelope-paper me-2 text-primary"></i>Pengajuan Izin Terbaru</h6>
            <a href="{{ route('leave-approvals.index') }}" class="btn btn-sm btn-link text-decoration-none">Lihat Semua</a>
          </div>
          @forelse ($recentLeaves as $lr)
            <div class="d-flex align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
              <div class="flex-grow-1">
                <div class="fw-semibold small">{{ $lr->user->name }}</div>
                <div class="small text-muted">{{ $lr->type_label }} &middot; {{ $lr->start_date->translatedFormat('d M') }}</div>
              </div>
              <span class="badge-soft badge-{{ $lr->status }}">{{ $lr->status_label }}</span>
            </div>
          @empty
            <p class="text-muted small mb-0">Belum ada pengajuan.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script type="module">
  import Chart from 'https://cdn.jsdelivr.net/npm/chart.js@4.4.7/+esm';
  const ctx = document.getElementById('trendChart');
  if (ctx) {
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: @json($trend->pluck('label')),
        datasets: [{
          label: 'Hadir',
          data: @json($trend->pluck('count')),
          borderColor: '#1e3a8a',
          backgroundColor: 'rgba(30,58,138,.1)',
          fill: true,
          tension: .35,
        }],
      },
      options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
      },
    });
  }
</script>
@endpush
