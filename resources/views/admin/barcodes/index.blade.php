@extends('layouts.app', ['header' => 'QR Code Lokasi', 'subheader' => 'Daftar QR Code yang digunakan untuk presensi.'])

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="text-muted small">Total: {{ $barcodes->total() }}</div>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.barcodes.downloadall') }}" class="btn btn-sm btn-outline-primary">
        <i class="bi bi-file-zip me-1"></i>Download Semua (ZIP)
      </a>
      <a href="{{ route('admin.barcodes.create') }}" class="btn btn-sm btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah QR Code
      </a>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th class="ps-3">Nama</th>
            <th>Kode</th>
            <th>Lokasi</th>
            <th>Radius</th>
            <th class="pe-3 text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($barcodes as $b)
            <tr>
              <td class="ps-3 fw-semibold">{{ $b->name ?? '—' }}</td>
              <td class="small"><code>{{ $b->value }}</code></td>
              <td class="small">
                @if ($b->latitude && $b->longitude)
                  <a href="https://maps.google.com/?q={{ $b->latitude }},{{ $b->longitude }}" target="_blank"
                     class="text-decoration-none"><i class="bi bi-geo-alt"></i> {{ number_format($b->latitude, 4) }}, {{ number_format($b->longitude, 4) }}</a>
                @else
                  —
                @endif
              </td>
              <td class="small">{{ $b->radius }} m</td>
              <td class="pe-3 text-end">
                <a href="{{ route('admin.barcodes.download', $b->id) }}" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-download"></i> PNG
                </a>
                <a href="{{ route('admin.barcodes.edit', $b) }}" class="btn btn-sm btn-outline-secondary">
                  <i class="bi bi-pencil"></i>
                </a>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada QR Code.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($barcodes->hasPages())
      <div class="p-3 border-top">{{ $barcodes->links() }}</div>
    @endif
  </div>
@endsection
