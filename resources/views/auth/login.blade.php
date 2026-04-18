@extends('layouts.guest')

@section('content')
  @if (session('status'))
    <div class="alert alert-success small">{{ session('status') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger small mb-3">
      <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label small fw-semibold">Email</label>
      <input type="email" name="email" class="form-control" required autofocus
             value="{{ old('email') }}" placeholder="nama@maznusantara.co.id">
    </div>
    <div class="mb-3">
      <label class="form-label small fw-semibold">Password</label>
      <input type="password" name="password" class="form-control" required placeholder="••••••••">
    </div>
    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="remember" id="remember">
      <label class="form-check-label small" for="remember">Ingat saya</label>
    </div>
    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
      <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
    </button>

    @if (Route::has('password.request'))
      <div class="text-center mt-3">
        <a href="{{ route('password.request') }}" class="small text-muted text-decoration-none">Lupa password?</a>
      </div>
    @endif
  </form>
@endsection
