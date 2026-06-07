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

    // Sidebar mobile: toggle + backdrop + auto-close
    const toggleBtn = document.querySelector('[data-sidebar-toggle]');
    const sidebar = document.querySelector('.app-sidebar');
    if (toggleBtn && sidebar) {
        const backdrop = document.createElement('div');
        backdrop.className = 'sidebar-backdrop';
        document.body.appendChild(backdrop);

        const openSidebar = () => {
            sidebar.classList.add('show');
            backdrop.classList.add('show');
            document.body.classList.add('sidebar-open');
        };
        const closeSidebar = () => {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
            document.body.classList.remove('sidebar-open');
        };
        const toggleSidebar = () =>
            sidebar.classList.contains('show') ? closeSidebar() : openSidebar();

        toggleBtn.addEventListener('click', toggleSidebar);
        backdrop.addEventListener('click', closeSidebar);
        // Tutup saat memilih menu (navigasi di mobile)
        sidebar.querySelectorAll('a.nav-link').forEach((link) =>
            link.addEventListener('click', closeSidebar)
        );
        // Tutup dengan tombol Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeSidebar();
        });
        // Reset state bila layar dibesarkan ke desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) closeSidebar();
        });
    }

    // Auto-bungkus tabel agar bisa di-scroll horizontal di layar sempit
    document.querySelectorAll('table.table').forEach((table) => {
        if (!table.closest('.table-responsive')) {
            const wrapper = document.createElement('div');
            wrapper.className = 'table-responsive';
            table.parentNode.insertBefore(wrapper, table);
            wrapper.appendChild(table);
        }
    });
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
