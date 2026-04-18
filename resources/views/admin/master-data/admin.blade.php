@extends('layouts.app', ['header' => 'Manajemen Akun', 'subheader' => 'Daftar akun HRD & Atasan Divisi.'])

@section('content')
  <div class="d-flex justify-content-between mb-3">
    <div class="text-muted small">Total: {{ $users->total() }}</div>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Tambah Akun
    </a>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th class="ps-3">Nama</th>
            <th>Email</th>
            <th>Divisi</th>
            <th>Role</th>
            <th>Status</th>
            <th class="pe-3 text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($users as $u)
            <tr>
              <td class="ps-3">
                <div class="d-flex align-items-center gap-2">
                  <img src="{{ $u->profile_photo_url }}" width="32" height="32" class="rounded-circle" style="object-fit:cover">
                  <div class="fw-semibold">{{ $u->name }}</div>
                </div>
              </td>
              <td class="small">{{ $u->email }}</td>
              <td class="small">{{ $u->division?->name ?? '—' }}</td>
              <td><span class="badge text-bg-light border">{{ $u->role_label }}</span></td>
              <td>
                @if ($u->isActive)
                  <span class="badge-soft badge-approved">Aktif</span>
                @else
                  <span class="badge-soft badge-rejected">Nonaktif</span>
                @endif
              </td>
              <td class="pe-3 text-end">
                <a href="{{ route('admin.employees.edit', $u) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada akun.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($users->hasPages())
      <div class="p-3 border-top">{{ $users->links() }}</div>
    @endif
  </div>
@endsection
