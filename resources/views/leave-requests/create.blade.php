@extends('layouts.app', ['header' => 'Ajukan Izin / Cuti', 'subheader' => 'Isi form di bawah untuk mengajukan izin.'])

@section('content')
  <div class="row">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <form method="POST" action="{{ route('leave-requests.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Jenis</label>
                <select name="leave_type" class="form-select" required>
                  <option value="izin" {{ old('leave_type')==='izin' ? 'selected':'' }}>Izin</option>
                  <option value="cuti" {{ old('leave_type')==='cuti' ? 'selected':'' }}>Cuti</option>
                  <option value="sakit" {{ old('leave_type')==='sakit' ? 'selected':'' }}>Sakit</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" required value="{{ old('start_date') }}">
              </div>
              <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" required value="{{ old('end_date') }}">
              </div>
              <div class="col-12">
                <label class="form-label small fw-semibold">Alasan</label>
                <textarea name="reason" rows="4" class="form-control" required minlength="10" placeholder="Jelaskan alasan pengajuan izin">{{ old('reason') }}</textarea>
              </div>
              <div class="col-12">
                <label class="form-label small fw-semibold">Lampiran <span class="text-muted">(opsional, max 3MB)</span></label>
                <input type="file" name="attachment" class="form-control" accept="image/*,application/pdf">
                <small class="text-muted">Untuk sakit, lampirkan surat dokter bila ada.</small>
              </div>
            </div>

            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-send me-2"></i>Kirim Pengajuan
              </button>
              <a href="{{ route('leave-requests.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm bg-light">
        <div class="card-body small">
          <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-1"></i>Catatan</h6>
          <ul class="ps-3 mb-0">
            <li>Pengajuan akan direview oleh Atasan Divisi Anda.</li>
            <li>Anda akan melihat status real-time di halaman pengajuan.</li>
            <li>Pengajuan yang masih <b>Menunggu</b> dapat dibatalkan.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection
