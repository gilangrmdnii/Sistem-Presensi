@extends('layouts.app', ['header' => 'Presensi Divisi', 'subheader' => 'Pantau kehadiran karyawan divisi Anda.'])

@section('content')
  <form method="GET" class="mb-3">
    <div class="input-group input-group-sm" style="max-width:260px">
      <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
      <input type="date" name="date" class="form-control" value="{{ $date }}">
      <button class="btn btn-primary">Tampilkan</button>
    </div>
  </form>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th class="ps-3">Karyawan</th>
            <th>Jam Masuk</th>
            <th>Jam Pulang</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($karyawan as $k)
            @php($a = $k->today_attendance)
            <tr>
              <td class="ps-3">
                <div class="fw-semibold">{{ $k->name }}</div>
                <div class="small text-muted">{{ $k->nip ?? '' }}</div>
              </td>
              <td>{{ $a?->time_in ?? '—' }}</td>
              <td>{{ $a?->time_out ?? '—' }}</td>
              <td>
                @if ($a)
                  <span class="badge-soft badge-{{ $a->status }}">{{ ucfirst($a->status) }}</span>
                @else
                  <span class="badge-soft badge-absent">Belum Absen</span>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada karyawan di divisi ini.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
