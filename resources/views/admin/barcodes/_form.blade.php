@php
  $isEdit = isset($barcode);
  $defaultLat = old('lat', $barcode->latitude ?? -6.2088);
  $defaultLng = old('lng', $barcode->longitude ?? 106.8456);
@endphp

<div class="row g-3">
  {{-- Kiri: Peta (besar, primary action) --}}
  <div class="col-lg-8">
    <label class="form-label small fw-semibold mb-2">
      <i class="bi bi-geo-alt-fill text-danger"></i>
      Pilih Lokasi Kantor
    </label>

    <div class="position-relative mb-2">
      {{-- Search bar di atas peta --}}
      <div class="position-absolute top-0 start-0 end-0 p-2" style="z-index:1000">
        <div class="input-group input-group-sm shadow-sm">
          <span class="input-group-text bg-white border-end-0">
            <i class="bi bi-search"></i>
          </span>
          <input type="text" id="addr-search" class="form-control border-start-0"
                 placeholder="Cari alamat (mis. Monas, Jakarta) lalu tekan Enter">
          <button type="button" class="btn btn-light border" id="locate-me" title="Pakai lokasi saya sekarang">
            <i class="bi bi-crosshair"></i> Lokasi Saya
          </button>
        </div>
        <div id="addr-result" class="mt-1 small bg-white shadow-sm rounded px-2 py-1 d-none" style="max-height:160px;overflow:auto"></div>
      </div>

      <div id="map" style="height:420px;border-radius:.5rem;border:1px solid #dee2e6"></div>
    </div>

    <div class="alert alert-light border small mb-0 d-flex align-items-center gap-2">
      <i class="bi bi-info-circle text-primary"></i>
      <span>
        <strong>Cara pakai:</strong> klik di peta untuk pindahkan marker, atau cari alamat di kotak pencarian, atau tekan <em>Lokasi Saya</em>.
      </span>
    </div>

    {{-- Hidden inputs (auto-filled by JS) --}}
    <input type="hidden" name="lat" id="lat" value="{{ $defaultLat }}">
    <input type="hidden" name="lng" id="lng" value="{{ $defaultLng }}">
  </div>

  {{-- Kanan: Detail QR Code --}}
  <div class="col-lg-4">
    <label class="form-label small fw-semibold mb-2">
      <i class="bi bi-qr-code text-primary"></i> Detail QR Code
    </label>

    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label small fw-semibold">Nama Lokasi *</label>
          <input type="text" name="name" class="form-control" required
                 value="{{ old('name', $barcode->name ?? '') }}"
                 placeholder="cth. Kantor Pusat Jakarta">
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Radius Valid Presensi *</label>
          <div class="input-group">
            <input type="number" name="radius" class="form-control" required min="10" max="5000"
                   value="{{ old('radius', $barcode->radius ?? 50) }}">
            <span class="input-group-text">meter</span>
          </div>
          <small class="text-muted">Karyawan harus berada dalam radius ini saat scan.</small>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Koordinat Terpilih</label>
          <div class="d-flex gap-2 small font-monospace">
            <span class="text-muted">Lat:</span>
            <span id="display-lat" class="fw-semibold">{{ number_format($defaultLat, 6) }}</span>
          </div>
          <div class="d-flex gap-2 small font-monospace">
            <span class="text-muted">Lng:</span>
            <span id="display-lng" class="fw-semibold">{{ number_format($defaultLng, 6) }}</span>
          </div>
        </div>

        <details class="small">
          <summary class="text-muted">Pengaturan Lanjutan</summary>
          <div class="mt-2">
            <label class="form-label small fw-semibold">Kode Unik (Value)</label>
            <input type="text" name="value" class="form-control form-control-sm font-monospace"
                   required value="{{ old('value', $barcode->value ?? 'MAZ-'.\Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(10))) }}">
            <small class="text-muted">Nilai yang di-encode ke QR Code.</small>
          </div>
        </details>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  (function () {
    const initLat = {{ $defaultLat }};
    const initLng = {{ $defaultLng }};
    const map = L.map('map').setView([initLat, initLng], 16);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);
    let radiusCircle = L.circle([initLat, initLng], {
      radius: parseInt(document.querySelector('[name=radius]').value || 50),
      color: '#1e3a8a', fillColor: '#1e3a8a', fillOpacity: 0.1
    }).addTo(map);

    function setLocation(lat, lng, zoom = null) {
      marker.setLatLng([lat, lng]);
      radiusCircle.setLatLng([lat, lng]);
      if (zoom) map.setView([lat, lng], zoom);
      document.getElementById('lat').value = lat;
      document.getElementById('lng').value = lng;
      document.getElementById('display-lat').textContent = Number(lat).toFixed(6);
      document.getElementById('display-lng').textContent = Number(lng).toFixed(6);
    }

    // Click di map → pindah marker
    map.on('click', (e) => setLocation(e.latlng.lat, e.latlng.lng));

    // Drag marker
    marker.on('dragend', () => {
      const p = marker.getLatLng();
      setLocation(p.lat, p.lng);
    });

    // Update radius circle saat radius diubah
    document.querySelector('[name=radius]').addEventListener('input', (e) => {
      const r = parseInt(e.target.value || 50);
      radiusCircle.setRadius(r);
    });

    // Tombol "Lokasi Saya"
    document.getElementById('locate-me').addEventListener('click', () => {
      if (!navigator.geolocation) return alert('Browser tidak support geolocation.');
      navigator.geolocation.getCurrentPosition(
        (pos) => setLocation(pos.coords.latitude, pos.coords.longitude, 17),
        (err) => alert('Gagal mendapat lokasi: ' + err.message)
      );
    });

    // Search alamat via Nominatim (OSM geocoding)
    const searchInput = document.getElementById('addr-search');
    const searchResult = document.getElementById('addr-result');
    let searchTimer;

    async function searchAddress(query) {
      if (!query || query.length < 3) {
        searchResult.classList.add('d-none');
        return;
      }
      try {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&countrycodes=id`;
        const res = await fetch(url, { headers: { 'Accept-Language': 'id' } });
        const data = await res.json();
        if (!data.length) {
          searchResult.innerHTML = '<div class="text-muted">Lokasi tidak ditemukan.</div>';
          searchResult.classList.remove('d-none');
          return;
        }
        searchResult.innerHTML = data.map((d, i) => `
          <a href="#" data-lat="${d.lat}" data-lon="${d.lon}" class="d-block py-1 text-decoration-none border-bottom">
            <i class="bi bi-geo-alt text-danger"></i> ${d.display_name}
          </a>
        `).join('');
        searchResult.classList.remove('d-none');

        searchResult.querySelectorAll('a').forEach((a) => {
          a.addEventListener('click', (e) => {
            e.preventDefault();
            setLocation(parseFloat(a.dataset.lat), parseFloat(a.dataset.lon), 17);
            searchResult.classList.add('d-none');
            searchInput.value = a.textContent.trim();
          });
        });
      } catch (err) {
        searchResult.innerHTML = '<div class="text-danger">Error pencarian.</div>';
        searchResult.classList.remove('d-none');
      }
    }

    searchInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        searchAddress(searchInput.value);
      }
    });

    // Hide search result on outside click
    document.addEventListener('click', (e) => {
      if (!searchResult.contains(e.target) && e.target !== searchInput) {
        searchResult.classList.add('d-none');
      }
    });
  })();
</script>
@endpush
