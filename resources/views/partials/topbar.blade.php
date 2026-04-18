@php($user = auth()->user())
<header class="app-topbar">
  <div class="d-flex align-items-center gap-2">
    <button type="button" class="btn btn-sm btn-outline-secondary d-lg-none" data-sidebar-toggle>
      <i class="bi bi-list"></i>
    </button>
    <div class="small text-muted">
      {{ now()->translatedFormat('l, d F Y') }}
    </div>
  </div>

  <div class="d-flex align-items-center gap-3">
    <span class="badge text-bg-light border">{{ $user->role_label }}</span>
    <div class="dropdown">
      <button class="btn btn-link text-decoration-none text-dark d-flex align-items-center gap-2 p-0"
              type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
             class="rounded-circle" width="32" height="32" style="object-fit:cover">
        <span class="d-none d-md-inline small fw-semibold">{{ $user->name }}</span>
        <i class="bi bi-chevron-down small"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm">
        <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</header>
