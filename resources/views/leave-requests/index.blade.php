@extends('layouts.app', ['header' => 'Pengajuan Izin / Cuti', 'subheader' => 'Riwayat pengajuan izin dan cuti Anda.'])

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-lg me-2"></i>Ajukan Izin Baru
    </a>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th class="ps-3">Tanggal Pengajuan</th>
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
              <td class="ps-3">{{ $lr->created_at->translatedFormat('d M Y H:i') }}</td>
              <td>{{ $lr->type_label }}</td>
              <td>{{ $lr->start_date->translatedFormat('d M Y') }} &mdash; {{ $lr->end_date->translatedFormat('d M Y') }}</td>
              <td>{{ $lr->duration_days }} hari</td>
              <td><span class="badge-soft badge-{{ $lr->status }}">{{ $lr->status_label }}</span></td>
              <td class="pe-3 text-end">
                <a href="{{ route('leave-requests.show', $lr) }}" class="btn btn-sm btn-outline-secondary">
                  <i class="bi bi-eye"></i>
                </a>
                @if ($lr->isPending)
                  <form action="{{ route('leave-requests.destroy', $lr) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Batalkan pengajuan ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i></button>
                  </form>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pengajuan.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($leaveRequests->hasPages())
      <div class="p-3 border-top">{{ $leaveRequests->links() }}</div>
    @endif
  </div>
@endsection
