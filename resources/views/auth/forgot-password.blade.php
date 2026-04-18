@extends('layouts.guest')

@section('content')
  <p class="text-muted small mb-3">Masukkan email, kami akan kirim link reset password.</p>

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

  <form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label small fw-semibold">Email</label>
      <input type="email" name="email" class="form-control" required autofocus value="{{ old('email') }}">
    </div>
    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Kirim Link Reset</button>
    <div class="text-center mt-3">
      <a href="{{ route('login') }}" class="small text-muted text-decoration-none">Kembali ke login</a>
    </div>
  </form>
@endsection
