@if (session('flash.banner'))
  @php($style = session('flash.bannerStyle', 'success'))
  <div class="alert alert-{{ $style === 'danger' ? 'danger' : ($style === 'warning' ? 'warning' : 'success') }} alert-dismissible fade show m-3 mb-0"
       role="alert" data-auto-dismiss>
    <i class="bi bi-{{ $style === 'danger' ? 'exclamation-triangle' : 'check-circle' }} me-2"></i>
    {{ session('flash.banner') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if ($errors->any())
  <div class="alert alert-danger alert-dismissible fade show m-3 mb-0" role="alert">
    <strong><i class="bi bi-exclamation-triangle me-2"></i>Terdapat kesalahan:</strong>
    <ul class="mb-0 mt-1 ps-3">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif
