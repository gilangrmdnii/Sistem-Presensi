@extends('layouts.app', ['header' => 'Review Pengajuan Izin'])

@section('content')
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <h5 class="fw-bold mb-1">{{ $leaveRequest->user->name }}</h5>
              <div class="small text-muted">{{ $leaveRequest->user->division?->name ?? '—' }} &middot; {{ $leaveRequest->user->email }}</div>
            </div>
            <span class="badge-soft badge-{{ $leaveRequest->status }}">{{ $leaveRequest->status_label }}</span>
          </div>

          <dl class="row small mb-0">
            <dt class="col-sm-4 text-muted">Jenis</dt>
            <dd class="col-sm-8">{{ $leaveRequest->type_label }}</dd>
            <dt class="col-sm-4 text-muted">Periode</dt>
            <dd class="col-sm-8">{{ $leaveRequest->start_date->translatedFormat('d F Y') }} &mdash; {{ $leaveRequest->end_date->translatedFormat('d F Y') }} ({{ $leaveRequest->duration_days }} hari)</dd>
            <dt class="col-sm-4 text-muted">Alasan</dt>
            <dd class="col-sm-8">{{ $leaveRequest->reason }}</dd>
            @if ($leaveRequest->attachment_url)
              <dt class="col-sm-4 text-muted">Lampiran</dt>
              <dd class="col-sm-8"><a href="{{ $leaveRequest->attachment_url }}" target="_blank"><i class="bi bi-file-earmark"></i> Buka Lampiran</a></dd>
            @endif
            <dt class="col-sm-4 text-muted">Diajukan</dt>
            <dd class="col-sm-8">{{ $leaveRequest->created_at->translatedFormat('d F Y H:i') }}</dd>
          </dl>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      @if ($leaveRequest->isPending)
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h6 class="fw-bold mb-3">Tindakan</h6>

            <form method="POST" action="{{ route('leave-approvals.approve', $leaveRequest) }}" class="mb-3">
              @csrf
              <textarea name="approver_note" class="form-control mb-2" rows="2" placeholder="Catatan persetujuan (opsional)"></textarea>
              <button type="submit" class="btn btn-success w-100" onclick="return confirm('Setujui pengajuan ini?')">
                <i class="bi bi-check2-circle me-2"></i>Setujui
              </button>
            </form>

            <form method="POST" action="{{ route('leave-approvals.reject', $leaveRequest) }}">
              @csrf
              <textarea name="approver_note" class="form-control mb-2" rows="2" placeholder="Alasan penolakan (wajib)" required></textarea>
              <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tolak pengajuan ini?')">
                <i class="bi bi-x-circle me-2"></i>Tolak
              </button>
            </form>
          </div>
        </div>
      @else
        <div class="card border-0 shadow-sm">
          <div class="card-body small">
            <h6 class="fw-bold mb-2">Hasil</h6>
            <dl class="row mb-0">
              <dt class="col-5 text-muted">Diproses oleh</dt>
              <dd class="col-7">{{ $leaveRequest->approver?->name ?? '—' }}</dd>
              <dt class="col-5 text-muted">Tanggal</dt>
              <dd class="col-7">{{ $leaveRequest->approved_at?->translatedFormat('d F Y H:i') }}</dd>
              @if ($leaveRequest->approver_note)
                <dt class="col-5 text-muted">Catatan</dt>
                <dd class="col-7">{{ $leaveRequest->approver_note }}</dd>
              @endif
            </dl>
          </div>
        </div>
      @endif
    </div>
  </div>

  <div class="mt-3">
    <a href="{{ route('leave-approvals.index') }}" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
  </div>
@endsection
