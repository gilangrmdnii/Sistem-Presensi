@extends('layouts.app', ['header' => 'Data Karyawan', 'subheader' => 'Kelola seluruh pengguna sistem presensi.'])

@section('content')
  <div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Cari</label>
          <input type="text" name="q" class="form-control form-control-sm" placeholder="Nama, email, NIP..." value="{{ request('q') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold">Role</label>
          <select name="role" class="form-select form-select-sm">
            <option value="">Semua</option>
            @foreach (\App\Models\User::$roleLabels as $k => $v)
              <option value="{{ $k }}" {{ request('role')===$k ? 'selected':'' }}>{{ $v }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold">Divisi</label>
          <select name="division" class="form-select form-select-sm">
            <option value="">Semua</option>
            @foreach ($divisions as $d)
              <option value="{{ $d->id }}" {{ request('division')==$d->id ? 'selected':'' }}>{{ $d->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
          <button class="btn btn-sm btn-primary flex-grow-1"><i class="bi bi-funnel"></i> Filter</button>
          <a href="{{ route('admin.employees') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
        </div>
      </form>
    </div>
  </div>

  <div class="d-flex justify-content-between mb-2">
    <div class="text-muted small">Total: {{ $employees->total() }}</div>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Tambah Karyawan
    </a>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th class="ps-3">NIP</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Divisi</th>
            <th>Role</th>
            <th>Status</th>
            <th class="pe-3 text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($employees as $e)
            <tr>
              <td class="ps-3 small text-muted">{{ $e->nip ?? '—' }}</td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <img src="{{ $e->profile_photo_url }}" width="32" height="32" class="rounded-circle" style="object-fit:cover">
                  <div>
                    <div class="fw-semibold">{{ $e->name }}</div>
                    <div class="small text-muted">{{ $e->phone ?? '' }}</div>
                  </div>
                </div>
              </td>
              <td class="small">{{ $e->email }}</td>
              <td class="small">{{ $e->division?->name ?? '—' }}</td>
              <td><span class="badge text-bg-light border">{{ $e->role_label }}</span></td>
              <td>
                @if ($e->isActive)
                  <span class="badge-soft badge-approved">Aktif</span>
                @else
                  <span class="badge-soft badge-rejected">Nonaktif</span>
                @endif
              </td>
              <td class="pe-3 text-end">
                <a href="{{ route('admin.employees.edit', $e) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                <form action="{{ route('admin.employees.destroy', $e) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus karyawan ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada karyawan.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($employees->hasPages())
      <div class="p-3 border-top">{{ $employees->links() }}</div>
    @endif
  </div>
@endsection
