@php($e = $employee ?? null)

<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label small fw-semibold">NIP</label>
    <input type="text" name="nip" class="form-control" value="{{ old('nip', $e?->nip) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label small fw-semibold">Nama Lengkap *</label>
    <input type="text" name="name" class="form-control" required value="{{ old('name', $e?->name) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label small fw-semibold">Email *</label>
    <input type="email" name="email" class="form-control" required value="{{ old('email', $e?->email) }}">
  </div>

  <div class="col-md-4">
    <label class="form-label small fw-semibold">No. Telepon *</label>
    <input type="text" name="phone" class="form-control" required value="{{ old('phone', $e?->phone) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label small fw-semibold">Gender *</label>
    <select name="gender" class="form-select" required>
      <option value="male" {{ old('gender', $e?->gender)==='male' ? 'selected':'' }}>Laki-laki</option>
      <option value="female" {{ old('gender', $e?->gender)==='female' ? 'selected':'' }}>Perempuan</option>
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label small fw-semibold">Status *</label>
    <select name="status" class="form-select" required>
      <option value="active" {{ old('status', $e?->status ?? 'active')==='active' ? 'selected':'' }}>Aktif</option>
      <option value="inactive" {{ old('status', $e?->status)==='inactive' ? 'selected':'' }}>Nonaktif</option>
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label small fw-semibold">Role *</label>
    <select name="role" class="form-select" required>
      @foreach ($roles as $k => $v)
        <option value="{{ $k }}" {{ old('role', $e?->role ?? 'karyawan')===$k ? 'selected':'' }}>{{ $v }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label small fw-semibold">Divisi</label>
    <select name="division_id" class="form-select">
      <option value="">—</option>
      @foreach ($divisions as $d)
        <option value="{{ $d->id }}" {{ old('division_id', $e?->division_id)==$d->id ? 'selected':'' }}>{{ $d->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label small fw-semibold">Jabatan</label>
    <select name="job_title_id" class="form-select">
      <option value="">—</option>
      @foreach ($jobTitles as $j)
        <option value="{{ $j->id }}" {{ old('job_title_id', $e?->job_title_id)==$j->id ? 'selected':'' }}>{{ $j->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label small fw-semibold">Pendidikan</label>
    <select name="education_id" class="form-select">
      <option value="">—</option>
      @foreach ($educations as $ed)
        <option value="{{ $ed->id }}" {{ old('education_id', $e?->education_id)==$ed->id ? 'selected':'' }}>{{ $ed->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label small fw-semibold">Tgl. Lahir</label>
    <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $e?->birth_date?->format('Y-m-d')) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label small fw-semibold">Tempat Lahir</label>
    <input type="text" name="birth_place" class="form-control" value="{{ old('birth_place', $e?->birth_place) }}">
  </div>

  <div class="col-md-8">
    <label class="form-label small fw-semibold">Alamat</label>
    <input type="text" name="address" class="form-control" value="{{ old('address', $e?->address) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label small fw-semibold">Kota</label>
    <input type="text" name="city" class="form-control" value="{{ old('city', $e?->city) }}">
  </div>

  <div class="col-md-6">
    <label class="form-label small fw-semibold">Password {!! $e ? '<span class="text-muted">(kosongkan jika tidak diubah)</span>' : '*' !!}</label>
    <input type="text" name="password" class="form-control" {{ $e ? '' : 'required' }}
           placeholder="{{ $e ? 'Biarkan kosong' : 'Min. 6 karakter' }}">
  </div>
</div>
