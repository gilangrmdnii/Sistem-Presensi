@extends('layouts.app', ['header' => 'Edit Karyawan: '.$employee->name])

@section('content')
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('admin.employees.update', $employee) }}">
        @csrf @method('PUT')
        @include('admin.employees._form')
        <div class="d-flex gap-2 mt-4">
          <button class="btn btn-primary"><i class="bi bi-save me-2"></i>Update</button>
          <a href="{{ route('admin.employees') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
@endsection
