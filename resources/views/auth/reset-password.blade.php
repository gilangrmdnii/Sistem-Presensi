@extends('layouts.guest')

@section('content')
  @if ($errors->any())
    <div class="alert alert-danger small mb-3">
      <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">
    <div class="mb-3">
      <label class="form-label small fw-semibold">Email</label>
      <input type="email" name="email" class="form-control" required value="{{ old('email', $request->email) }}">
    </div>
    <div class="mb-3">
      <label class="form-label small fw-semibold">Password Baru</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label small fw-semibold">Konfirmasi Password</label>
      <input type="password" name="password_confirmation" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Reset Password</button>
  </form>
@endsection
