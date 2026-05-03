@php($user = auth()->user())
<aside class="app-sidebar">
  <div class="sidebar-brand">
    <div class="brand-logo">MAZ</div>
    <div>
      <div class="brand-name">Presensi MAZ</div>
      <div class="brand-sub">PT MAZ Nusantara Cakti</div>
    </div>
  </div>

  <nav class="sidebar-nav">
    @if ($user->isKaryawan)
      <div class="nav-group-title">Karyawan</div>
      <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
      <a class="nav-link {{ request()->routeIs('attendance.scan') ? 'active' : '' }}" href="{{ route('attendance.scan') }}">
        <i class="bi bi-qr-code-scan"></i> Presensi QR Code
      </a>
      <a class="nav-link {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}" href="{{ route('leave-requests.index') }}">
        <i class="bi bi-envelope-paper"></i> Pengajuan Izin
      </a>
      <a class="nav-link {{ request()->routeIs('attendance-history') ? 'active' : '' }}" href="{{ route('attendance-history') }}">
        <i class="bi bi-clock-history"></i> Riwayat Presensi
      </a>
    @endif

    @if ($user->isAtasanDivisi)
      <div class="nav-group-title">Atasan Divisi</div>
      <a class="nav-link {{ request()->routeIs('leave-approvals.*') ? 'active' : '' }}" href="{{ route('leave-approvals.index') }}">
        <i class="bi bi-check2-square"></i> Persetujuan Izin
      </a>
      <a class="nav-link {{ request()->routeIs('atasan.attendances') ? 'active' : '' }}" href="{{ route('atasan.attendances') }}">
        <i class="bi bi-calendar-check"></i> Presensi Divisi
      </a>
    @endif

    @if ($user->isHrd)
      <div class="nav-group-title">HRD</div>
      <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
      <a class="nav-link {{ request()->routeIs('admin.employees') ? 'active' : '' }}" href="{{ route('admin.employees') }}">
        <i class="bi bi-people"></i> Data Karyawan
      </a>
      <a class="nav-link {{ request()->routeIs('admin.attendances*') ? 'active' : '' }}" href="{{ route('admin.attendances') }}">
        <i class="bi bi-calendar-check"></i> Data Presensi
      </a>
      <a class="nav-link {{ request()->routeIs('leave-approvals.*') ? 'active' : '' }}" href="{{ route('leave-approvals.index') }}">
        <i class="bi bi-envelope-check"></i> Data Izin / Cuti
      </a>
      <a class="nav-link {{ request()->routeIs('admin.barcodes*') ? 'active' : '' }}" href="{{ route('admin.barcodes') }}">
        <i class="bi bi-qr-code"></i> QR Code Lokasi
      </a>

      <div class="nav-group-title">Master Data</div>
      <a class="nav-link {{ request()->routeIs('admin.masters.division') ? 'active' : '' }}" href="{{ route('admin.masters.division') }}">
        <i class="bi bi-diagram-3"></i> Divisi
      </a>
      <a class="nav-link {{ request()->routeIs('admin.masters.job-title') ? 'active' : '' }}" href="{{ route('admin.masters.job-title') }}">
        <i class="bi bi-briefcase"></i> Jabatan
      </a>
      <a class="nav-link {{ request()->routeIs('admin.masters.education') ? 'active' : '' }}" href="{{ route('admin.masters.education') }}">
        <i class="bi bi-mortarboard"></i> Pendidikan
      </a>
      <a class="nav-link {{ request()->routeIs('admin.masters.admin') ? 'active' : '' }}" href="{{ route('admin.masters.admin') }}">
        <i class="bi bi-shield-lock"></i> Manajemen Akun
      </a>
      <a class="nav-link {{ request()->routeIs('admin.import-export*') ? 'active' : '' }}" href="{{ route('admin.import-export.users') }}">
        <i class="bi bi-filetype-xlsx"></i> Import / Export
      </a>
      <a class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
        <i class="bi bi-gear"></i> Pengaturan
      </a>
    @endif

    <div class="nav-group-title">Akun</div>
    <a class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}" href="{{ route('profile.show') }}">
      <i class="bi bi-person"></i> Profil
    </a>
    <form method="POST" action="{{ route('logout') }}" class="mt-1">
      @csrf
      <button type="submit" class="nav-link w-100 text-start bg-transparent border-0">
        <i class="bi bi-box-arrow-right"></i> Logout
      </button>
    </form>
  </nav>

  <div class="sidebar-footer">
    TA &mdash; Ahmad Nur Aziz<br>STT Nurul Fikri
  </div>
</aside>
