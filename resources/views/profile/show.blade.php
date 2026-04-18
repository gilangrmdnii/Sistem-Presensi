@extends('layouts.app', ['header' => 'Profil Saya', 'subheader' => 'Kelola informasi akun dan password Anda.'])

@section('content')
  <div class="row g-3">
    {{-- Sidebar Ringkasan --}}
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center">
          <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
               class="rounded-circle mb-3" width="120" height="120" style="object-fit:cover">
          <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
          <div class="text-muted small mb-2">{{ $user->email }}</div>
          <span class="badge-soft badge-approved">{{ $user->role_label }}</span>

          <hr>

          <dl class="row small text-start mb-0">
            <dt class="col-5 text-muted">NIP</dt>
            <dd class="col-7">{{ $user->nip ?? '—' }}</dd>
            <dt class="col-5 text-muted">Divisi</dt>
            <dd class="col-7">{{ $user->division?->name ?? '—' }}</dd>
            <dt class="col-5 text-muted">Jabatan</dt>
            <dd class="col-7">{{ $user->jobTitle?->name ?? '—' }}</dd>
            <dt class="col-5 text-muted">Bergabung</dt>
            <dd class="col-7">{{ $user->created_at->translatedFormat('d M Y') }}</dd>
          </dl>
        </div>
      </div>
    </div>

    {{-- Main Forms --}}
    <div class="col-lg-8">
      {{-- Update Profile --}}
      <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
          <h6 class="fw-bold mb-1"><i class="bi bi-person-gear me-2 text-primary"></i>Informasi Profil</h6>
          <p class="text-muted small mb-3">Perbarui nama, email, dan foto profil Anda.</p>

          @if ($errors->updateProfileInformation->any())
            <div class="alert alert-danger small py-2">
              <ul class="mb-0 ps-3">
                @foreach ($errors->updateProfileInformation->all() as $error)<li>{{ $error }}</li>@endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('user-profile-information.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="d-flex align-items-center gap-3 mb-4">
              <img id="photo-preview" src="{{ $user->profile_photo_url }}" class="rounded-circle"
                   width="64" height="64" style="object-fit:cover">
              <div>
                <label class="btn btn-sm btn-outline-primary mb-1">
                  <i class="bi bi-camera me-1"></i>Pilih Foto Baru
                  <input type="file" name="photo" class="d-none" accept="image/*"
                         onchange="document.getElementById('photo-preview').src = URL.createObjectURL(this.files[0])">
                </label>
                @if ($user->profile_photo_path)
                  <form method="POST" action="{{ route('profile.photo.delete') }}" class="d-inline"
                        onsubmit="return confirm('Hapus foto profil?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>Hapus</button>
                  </form>
                @endif
                <div class="small text-muted mt-1">JPG / PNG, maksimal 1MB.</div>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name', $user->name) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-semibold">NIP</label>
                <input type="text" name="nip" class="form-control" value="{{ old('nip', $user->nip) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-semibold">No. Telepon</label>
                <input type="text" name="phone" class="form-control" required value="{{ old('phone', $user->phone) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Gender</label>
                <select name="gender" class="form-select" required>
                  <option value="male" {{ old('gender', $user->gender)==='male' ? 'selected':'' }}>Laki-laki</option>
                  <option value="female" {{ old('gender', $user->gender)==='female' ? 'selected':'' }}>Perempuan</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Tempat Lahir</label>
                <input type="text" name="birth_place" class="form-control" value="{{ old('birth_place', $user->birth_place) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Tanggal Lahir</label>
                <input type="date" name="birth_date" class="form-control"
                       value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Kota</label>
                <input type="text" name="city" class="form-control" required value="{{ old('city', $user->city) }}">
              </div>
              <div class="col-12">
                <label class="form-label small fw-semibold">Alamat</label>
                <input type="text" name="address" class="form-control" required value="{{ old('address', $user->address) }}">
              </div>

              <div class="col-md-4">
                <label class="form-label small fw-semibold">Divisi</label>
                <select name="division_id" class="form-select">
                  <option value="">—</option>
                  @foreach ($divisions as $d)
                    <option value="{{ $d->id }}" {{ old('division_id', $user->division_id)==$d->id ? 'selected':'' }}>{{ $d->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-semibold">Jabatan</label>
                <select name="job_title_id" class="form-select">
                  <option value="">—</option>
                  @foreach ($jobTitles as $j)
                    <option value="{{ $j->id }}" {{ old('job_title_id', $user->job_title_id)==$j->id ? 'selected':'' }}>{{ $j->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-semibold">Pendidikan</label>
                <select name="education_id" class="form-select">
                  <option value="">—</option>
                  @foreach ($educations as $ed)
                    <option value="{{ $ed->id }}" {{ old('education_id', $user->education_id)==$ed->id ? 'selected':'' }}>{{ $ed->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="text-end mt-3">
              <button class="btn btn-primary"><i class="bi bi-save me-2"></i>Simpan Perubahan</button>
            </div>
          </form>
        </div>
      </div>

      {{-- Update Password --}}
      <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
          <h6 class="fw-bold mb-1"><i class="bi bi-shield-lock me-2 text-primary"></i>Ganti Password</h6>
          <p class="text-muted small mb-3">Pastikan menggunakan password yang kuat dan tidak digunakan di layanan lain.</p>

          @if ($errors->updatePassword->any())
            <div class="alert alert-danger small py-2">
              <ul class="mb-0 ps-3">
                @foreach ($errors->updatePassword->all() as $error)<li>{{ $error }}</li>@endforeach
              </ul>
            </div>
          @endif

          @if (session('status') === 'password-updated')
            <div class="alert alert-success small py-2">Password berhasil diperbarui.</div>
          @endif

          <form method="POST" action="{{ route('user-password.update') }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label small fw-semibold">Password Saat Ini</label>
                <input type="password" name="current_password" class="form-control" required autocomplete="current-password">
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-semibold">Password Baru</label>
                <input type="password" name="password" class="form-control" required autocomplete="new-password">
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-semibold">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
              </div>
            </div>

            <div class="text-end mt-3">
              <button class="btn btn-primary"><i class="bi bi-key me-2"></i>Ganti Password</button>
            </div>
          </form>
        </div>
      </div>

      {{-- Logout --}}
      <div class="card border-0 shadow-sm border-start border-4 border-danger">
        <div class="card-body">
          <h6 class="fw-bold mb-1 text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar Akun</h6>
          <p class="text-muted small mb-3">Logout dari sesi saat ini.</p>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-danger">
              <i class="bi bi-box-arrow-right me-2"></i>Logout
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
