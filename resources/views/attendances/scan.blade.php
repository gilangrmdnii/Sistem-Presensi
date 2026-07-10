@extends('layouts.app', ['header' => 'Presensi QR Code', 'subheader' => 'Pindai QR Code kantor untuk mencatat kehadiran Anda.'])

@section('content')
  <div class="row g-3">
    <div class="col-lg-7">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-qr-code-scan me-2 text-primary"></i>Pemindai</h6>

          <div class="qr-scanner-wrap">
            <div id="qr-reader" style="width:100%"></div>
            <div id="qr-status" class="text-center small text-muted mt-2">
              <i class="bi bi-info-circle me-1"></i>Izinkan akses kamera & lokasi.
            </div>

            <div class="text-center text-muted small my-2">— atau —</div>
            <label class="btn btn-outline-secondary btn-sm w-100 mb-0">
              <i class="bi bi-upload me-2"></i>Upload Gambar QR
              <input type="file" id="qr-file" accept="image/*" hidden>
            </label>
            <div class="text-center text-muted" style="font-size:.72rem">
              Tidak bisa pakai kamera? Unggah foto/screenshot QR kantor.
            </div>
          </div>

          <form id="scan-form" method="POST" action="{{ route('attendance.store') }}" class="mt-3">
            @csrf
            <input type="hidden" name="qr_value" id="qr_value">
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <button type="submit" id="scan-submit" class="btn btn-primary w-100" disabled>
              <i class="bi bi-check-circle me-2"></i>Kirim Presensi
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-clock me-2 text-primary"></i>Status Hari Ini</h6>
          @if ($today)
            <dl class="row small mb-0">
              <dt class="col-5 text-muted">Status</dt>
              <dd class="col-7"><span class="badge-soft badge-{{ $today->status }}">{{ $today->status_label }}</span></dd>
              <dt class="col-5 text-muted">Jam Masuk</dt>
              <dd class="col-7">{{ $today->time_in ?? '—' }}</dd>
              <dt class="col-5 text-muted">Jam Pulang</dt>
              <dd class="col-7">{{ $today->time_out ?? '—' }}</dd>
            </dl>
          @else
            <p class="text-muted small mb-0">Belum presensi hari ini.</p>
          @endif
        </div>
      </div>

      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Jam Kerja</h6>
          <dl class="row small mb-0">
            <dt class="col-5 text-muted">Masuk</dt>
            <dd class="col-7">{{ $workStart }}</dd>
            <dt class="col-5 text-muted">Pulang</dt>
            <dd class="col-7">{{ $workEnd }}</dd>
            <dt class="col-5 text-muted">Toleransi</dt>
            <dd class="col-7">{{ $tolerance }} menit</dd>
          </dl>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
  (function () {
    const qrValueEl = document.getElementById('qr_value');
    const latEl = document.getElementById('latitude');
    const lngEl = document.getElementById('longitude');
    const submit = document.getElementById('scan-submit');
    const status = document.getElementById('qr-status');

    function enableSubmitIfReady() {
      if (qrValueEl.value && latEl.value && lngEl.value) {
        submit.disabled = false;
      }
    }

    if (!('geolocation' in navigator)) {
      status.innerHTML = '<span class="text-danger">Perangkat tidak mendukung geolokasi.</span>';
      return;
    }

    navigator.geolocation.getCurrentPosition((pos) => {
      latEl.value = pos.coords.latitude;
      lngEl.value = pos.coords.longitude;
      status.innerHTML = '<i class="bi bi-geo-alt-fill text-success me-1"></i>Lokasi terdeteksi.';
      enableSubmitIfReady();
    }, (err) => {
      status.innerHTML = '<span class="text-danger">Gagal mendapat lokasi: '+err.message+'</span>';
    });

    const qr = new Html5Qrcode('qr-reader');
    qr.start(
      { facingMode: 'environment' },
      { fps: 10, qrbox: { width: 250, height: 250 } },
      (decoded) => {
        qrValueEl.value = decoded;
        status.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i>QR terdeteksi. Tekan tombol kirim.';
        enableSubmitIfReady();
        qr.stop().catch(() => {});
      },
      () => {}
    ).catch((err) => {
      status.innerHTML = '<span class="text-danger">Gagal akses kamera: '+err+'. Anda bisa unggah gambar QR di bawah.</span>';
    });

    // Alternatif kamera: baca QR dari gambar yang diunggah
    const fileInput = document.getElementById('qr-file');
    fileInput.addEventListener('change', async (e) => {
      const file = e.target.files && e.target.files[0];
      if (!file) return;
      status.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Membaca QR dari gambar...';
      // Kamera harus dihentikan dulu sebelum memindai berkas
      try { await qr.stop(); } catch (_) {}
      try {
        const decoded = await qr.scanFile(file, true);
        qrValueEl.value = decoded;
        status.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i>QR terbaca dari gambar. Tekan tombol kirim.';
        enableSubmitIfReady();
      } catch (err) {
        status.innerHTML = '<span class="text-danger">QR tidak terbaca dari gambar. Pastikan gambar jelas & berisi QR kantor.</span>';
      } finally {
        fileInput.value = '';
      }
    });
  })();
</script>
@endpush
