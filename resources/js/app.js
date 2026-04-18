import './bootstrap';
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

// Auto-dismiss flash alerts after 5s
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.alert-dismissible[data-auto-dismiss]').forEach((el) => {
        setTimeout(() => {
            bootstrap.Alert.getOrCreateInstance(el).close();
        }, 5000);
    });

    // Sidebar toggle (mobile)
    const toggleBtn = document.querySelector('[data-sidebar-toggle]');
    const sidebar = document.querySelector('.app-sidebar');
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => sidebar.classList.toggle('show'));
    }
});

// Leaflet lazy wrapper (only when map container exists)
window.initializeMap = async ({ onUpdate, location }) => {
    const L = (await import('leaflet')).default;
    await import('leaflet/dist/leaflet.css');

    const defaultLocation = location ?? [-6.2088, 106.8456];
    const map = L.map('map').setView(defaultLocation, 15);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

    const marker = L.marker(defaultLocation, { draggable: true }).addTo(map);
    marker.on('dragend', () => {
        const p = marker.getLatLng();
        onUpdate?.(p.lat, p.lng);
    });
    map.on('click', (e) => {
        marker.setLatLng(e.latlng);
        onUpdate?.(e.latlng.lat, e.latlng.lng);
    });
    onUpdate?.(defaultLocation[0], defaultLocation[1]);
    window._leafletMap = map;
};

window.setMapLocation = ({ location }) => {
    if (!location || !window._leafletMap) return;
    window._leafletMap.setView(location, 15);
};
