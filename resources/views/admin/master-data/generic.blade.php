@extends('layouts.app', ['header' => 'Master Data: '.$config['title']])

@section('content')
  <div class="row g-3">
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Baru</h6>
          <form method="POST" action="{{ route($config['route']) }}">
            @csrf
            @foreach ($config['fields'] as $f)
              <div class="mb-3">
                <label class="form-label small fw-semibold">{{ $f['label'] }}</label>
                <input type="text" name="{{ $f['name'] }}" class="form-control" required>
              </div>
            @endforeach
            <button class="btn btn-primary w-100"><i class="bi bi-save me-2"></i>Simpan</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th class="ps-3" style="width:60px">#</th>
                <th>Nama</th>
                <th class="pe-3 text-end" style="width:80px">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($items as $i => $item)
                <tr>
                  <td class="ps-3 text-muted">{{ $items->firstItem() + $i }}</td>
                  <td>{{ $item->name }}</td>
                  <td class="pe-3 text-end">
                    <form method="POST" action="{{ route($config['route']) }}" class="d-inline"
                          onsubmit="return confirm('Hapus data ini?')">
                      @csrf @method('DELETE')
                      <input type="hidden" name="_id" value="{{ $item->id }}">
                      <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if ($items->hasPages())
          <div class="p-3 border-top">{{ $items->links() }}</div>
        @endif
      </div>
    </div>
  </div>
@endsection
