@extends('layouts.app', ['header' => 'Persetujuan Izin', 'subheader' => 'Kelola pengajuan izin / cuti karyawan divisi Anda.'])

@section('content')
  <ul class="nav nav-pills mb-3">
    @foreach (['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'all' => 'Semua'] as $key => $label)
      <li class="nav-item">
        <a class="nav-link {{ $status === $key ? 'active' : '' }}" href="{{ route('leave-approvals.index', ['status' => $key]) }}">
          {{ $label }}
          @if (isset($counts[$key]))
            <span class="badge bg-white text-dark ms-1">{{ $counts[$key] }}</span>
          @endif
        </a>
      </li>
    @endforeach
  </ul>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th class="ps-3">Karyawan</th>
            <th>Divisi</th>
            <th>Jenis</th>
            <th>Periode</th>
            <th>Durasi</th>
            <th>Status</th>
            <th class="pe-3 text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($leaveRequests as $lr)
            <tr>
              <td class="ps-3">
                <div class="fw-semibold">{{ $lr->user->name }}</div>
                <div class="small text-muted">{{ $lr->user->email }}</div>
              </td>
              <td class="small">{{ $lr->user->division?->name ?? '—' }}</td>
              <td>{{ $lr->type_label }}</td>
              <td class="small">{{ $lr->start_date->translatedFormat('d M Y') }} &mdash; {{ $lr->end_date->translatedFormat('d M Y') }}</td>
              <td>{{ $lr->duration_days }}h</td>
              <td><span class="badge-soft badge-{{ $lr->status }}">{{ $lr->status_label }}</span></td>
              <td class="pe-3 text-end">
                <a href="{{ route('leave-approvals.show', $lr) }}" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-eye"></i> Review
                </a>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada pengajuan.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($leaveRequests->hasPages())
      <div class="p-3 border-top">{{ $leaveRequests->links() }}</div>
    @endif
  </div>
@endsection
