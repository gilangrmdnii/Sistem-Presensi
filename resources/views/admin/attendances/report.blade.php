<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Laporan Presensi {{ $start->toDateString() }} - {{ $end->toDateString() }}</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 20px; font-size: 11px; }
    h1 { font-size: 18px; margin: 0 0 4px; }
    .sub { color: #555; margin-bottom: 16px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #aaa; padding: 6px 8px; }
    th { background: #f2f2f2; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: .05em; }
    tr:nth-child(even) { background: #fafafa; }
  </style>
</head>
<body>
  <h1>Laporan Presensi Karyawan</h1>
  <div class="sub">PT MAZ Nusantara Cakti &middot; Periode {{ $start->translatedFormat('d M Y') }} &mdash; {{ $end->translatedFormat('d M Y') }}</div>

  <table>
    <thead>
      <tr>
        <th style="width:30px">No</th>
        <th>Tanggal</th>
        <th>Nama</th>
        <th>NIP</th>
        <th>Divisi</th>
        <th>Masuk</th>
        <th>Pulang</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($rows as $r)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ \Illuminate\Support\Carbon::parse($r->date)->translatedFormat('d M Y') }}</td>
          <td>{{ $r->user?->name }}</td>
          <td>{{ $r->user?->nip }}</td>
          <td>{{ $r->user?->division?->name ?? '-' }}</td>
          <td>{{ $r->time_in ?? '-' }}</td>
          <td>{{ $r->time_out ?? '-' }}</td>
          <td>{{ ucfirst($r->status) }}</td>
        </tr>
      @empty
        <tr><td colspan="8" style="text-align:center">Tidak ada data.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="sub" style="margin-top: 24px; text-align: right;">Dicetak: {{ now()->translatedFormat('d F Y H:i') }}</div>
</body>
</html>
