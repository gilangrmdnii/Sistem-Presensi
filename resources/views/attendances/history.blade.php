@extends('layouts.app', ['header' => 'Riwayat Presensi', 'subheader' => 'Rekap presensi Anda bulan ini.'])

@section('content')
  @php
    use Illuminate\Support\Carbon;
    $month = request('month', now()->format('Y-m'));
    $start = Carbon::parse($month.'-01');
    $end = $start->copy()->endOfMonth();
    $attendances = \App\Models\Attendance::where('user_id', auth()->id())
        ->whereBetween('date', [$start, $end])
        ->orderBy('date', 'desc')
        ->get()
        ->keyBy(fn ($a) => Carbon::parse($a->date)->toDateString());

    $stats = [
      'present' => 0, 'late' => 0, 'excused' => 0, 'sick' => 0, 'absent' => 0,
    ];
    foreach ($attendances as $a) {
        $stats[$a->status] = ($stats[$a->status] ?? 0) + 1;
    }
  @endphp

  <form method="GET" class="mb-3">
    <div class="input-group input-group-sm" style="max-width:280px">
      <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
      <input type="month" name="month" class="form-control" value="{{ $month }}">
      <button class="btn btn-primary">Tampilkan</button>
    </div>
  </form>

  <div class="row g-2 mb-3">
    @foreach ([
      ['Hadir', $stats['present'] ?? 0, 'success', 'person-check'],
      ['Terlambat', $stats['late'] ?? 0, 'warning', 'clock'],
      ['Izin', $stats['excused'] ?? 0, 'info', 'envelope-paper'],
      ['Sakit', $stats['sick'] ?? 0, 'info', 'heart-pulse'],
      ['Alpha', $stats['absent'] ?? 0, 'danger', 'person-x'],
    ] as [$label, $val, $clr, $icon])
      <div class="col-6 col-md">
        <div class="card border-0 shadow-sm">
          <div class="card-body py-3 text-center">
            <i class="bi bi-{{ $icon }} text-{{ $clr }} fs-4"></i>
            <div class="fw-bold fs-4 mt-1">{{ $val }}</div>
            <div class="text-muted small">{{ $label }}</div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th class="ps-3">Tanggal</th>
            <th>Hari</th>
            <th>Masuk</th>
            <th>Pulang</th>
            <th>Status</th>
            <th class="pe-3">Catatan</th>
          </tr>
        </thead>
        <tbody>
          @foreach (Carbon::parse($month.'-01')->range($end) as $date)
            @php($a = $attendances->get($date->toDateString()))
            <tr class="{{ $date->isWeekend() ? 'table-light text-muted' : '' }}">
              <td class="ps-3">{{ $date->translatedFormat('d M Y') }}</td>
              <td class="small">{{ $date->translatedFormat('l') }}</td>
              <td>{{ $a?->time_in ?? '—' }}</td>
              <td>{{ $a?->time_out ?? '—' }}</td>
              <td>
                @if ($a)
                  <span class="badge-soft badge-{{ $a->status }}">{{ ucfirst($a->status) }}</span>
                @elseif ($date->isWeekend())
                  <span class="small text-muted">Libur</span>
                @elseif ($date->isFuture())
                  <span class="small text-muted">—</span>
                @else
                  <span class="badge-soft badge-absent">Alpha</span>
                @endif
              </td>
              <td class="pe-3 small text-muted">{{ $a?->note ?? '' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
