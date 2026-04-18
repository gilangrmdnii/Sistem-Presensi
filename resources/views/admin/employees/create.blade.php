@extends('layouts.app', ['header' => 'Tambah Karyawan'])

@section('content')
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('admin.employees.store') }}">
        @csrf
        @include('admin.employees._form')
        <div class="d-flex gap-2 mt-4">
          <button class="btn btn-primary"><i class="bi bi-save me-2"></i>Simpan</button>
          <a href="{{ route('admin.employees') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
@endsection
