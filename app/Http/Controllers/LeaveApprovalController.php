<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveApprovalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        $user = Auth::user();

        $query = LeaveRequest::with('user.division')
            ->when($user->isAtasanDivisi, function ($q) use ($user) {
                $q->whereHas('user', fn ($qq) => $qq->where('division_id', $user->division_id));
            })
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest();

        $leaveRequests = $query->paginate(15)->withQueryString();

        $counts = [
            'pending' => $this->scopedQuery($user)->where('status', LeaveRequest::STATUS_PENDING)->count(),
            'approved' => $this->scopedQuery($user)->where('status', LeaveRequest::STATUS_APPROVED)->count(),
            'rejected' => $this->scopedQuery($user)->where('status', LeaveRequest::STATUS_REJECTED)->count(),
        ];

        return view('leave-approvals.index', compact('leaveRequests', 'status', 'counts'));
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $this->authorizeApprover($leaveRequest);
        $leaveRequest->load('user.division', 'approver');
        return view('leave-approvals.show', compact('leaveRequest'));
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorizeApprover($leaveRequest);
        $request->validate(['approver_note' => ['nullable', 'string', 'max:500']]);

        DB::transaction(function () use ($leaveRequest, $request) {
            $leaveRequest->update([
                'status' => LeaveRequest::STATUS_APPROVED,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'approver_note' => $request->approver_note,
            ]);
        });

        return redirect()->route('leave-approvals.index')
            ->with('flash.banner', 'Pengajuan izin disetujui.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorizeApprover($leaveRequest);
        $request->validate(['approver_note' => ['required', 'string', 'max:500']]);

        $leaveRequest->update([
            'status' => LeaveRequest::STATUS_REJECTED,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approver_note' => $request->approver_note,
        ]);

        return redirect()->route('leave-approvals.index')
            ->with('flash.banner', 'Pengajuan izin ditolak.')
            ->with('flash.bannerStyle', 'danger');
    }

    private function authorizeApprover(LeaveRequest $leaveRequest): void
    {
        $user = Auth::user();
        if ($user->isHrd) {
            return;
        }
        if ($user->isAtasanDivisi && $leaveRequest->user->division_id === $user->division_id) {
            return;
        }
        abort(403);
    }

    private function scopedQuery(User $user)
    {
        return LeaveRequest::query()
            ->when($user->isAtasanDivisi, function ($q) use ($user) {
                $q->whereHas('user', fn ($qq) => $qq->where('division_id', $user->division_id));
            });
    }
}
