<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? config('app.name') }}</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

  @vite(['resources/scss/app.scss', 'resources/js/app.js'])
  @livewireStyles
  @stack('styles')
</head>
<body>
  <div class="app-wrapper">
    @include('partials.sidebar')

    <div class="app-main">
      @include('partials.topbar')

      @includeIf('partials.flash')

      <main class="app-content">
        @if (isset($header))
          <div class="mb-4">
            <h1 class="h4 mb-1 fw-bold">{{ $header }}</h1>
            @if (isset($subheader))
              <p class="text-muted small mb-0">{{ $subheader }}</p>
            @endif
          </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
      </main>

      <footer class="text-center py-3 small text-muted">
        &copy; {{ date('Y') }} PT MAZ Nusantara Cakti &middot; Sistem Presensi
      </footer>
    </div>
  </div>

  @stack('modals')
  @livewireScripts
  @stack('scripts')
</body>
</html>
