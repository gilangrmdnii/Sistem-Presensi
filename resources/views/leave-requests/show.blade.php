@extends('layouts.app', ['header' => 'Detail Pengajuan Izin'])

@section('content')
  <div class="row">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <h5 class="fw-bold mb-0">{{ $leaveRequest->type_label }}</h5>
            <span class="badge-soft badge-{{ $leaveRequest->status }}">{{ $leaveRequest->status_label }}</span>
          </div>

          <dl class="row small mb-0">
            <dt class="col-sm-4 text-muted">Periode</dt>
            <dd class="col-sm-8">{{ $leaveRequest->start_date->translatedFormat('d F Y') }} &mdash; {{ $leaveRequest->end_date->translatedFormat('d F Y') }}</dd>

            <dt class="col-sm-4 text-muted">Durasi</dt>
            <dd class="col-sm-8">{{ $leaveRequest->duration_days }} hari</dd>

            <dt class="col-sm-4 text-muted">Alasan</dt>
            <dd class="col-sm-8">{{ $leaveRequest->reason }}</dd>

            @if ($leaveRequest->attachment_url)
              <dt class="col-sm-4 text-muted">Lampiran</dt>
              <dd class="col-sm-8"><a href="{{ $leaveRequest->attachment_url }}" target="_blank" class="link-primary"><i class="bi bi-file-earmark"></i> Lihat Lampiran</a></dd>
            @endif

            <dt class="col-sm-4 text-muted">Diajukan</dt>
            <dd class="col-sm-8">{{ $leaveRequest->created_at->translatedFormat('d F Y H:i') }}</dd>

            @if ($leaveRequest->approver)
              <dt class="col-sm-4 text-muted">Diproses oleh</dt>
              <dd class="col-sm-8">{{ $leaveRequest->approver->name }}</dd>

              <dt class="col-sm-4 text-muted">Tanggal Proses</dt>
              <dd class="col-sm-8">{{ $leaveRequest->approved_at?->translatedFormat('d F Y H:i') }}</dd>

              @if ($leaveRequest->approver_note)
                <dt class="col-sm-4 text-muted">Catatan Atasan</dt>
                <dd class="col-sm-8">{{ $leaveRequest->approver_note }}</dd>
              @endif
            @endif
          </dl>

          <a href="{{ route('leave-requests.index') }}" class="btn btn-outline-secondary mt-3">
            <i class="bi bi-arrow-left me-2"></i>Kembali
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection
