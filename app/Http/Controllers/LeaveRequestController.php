<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::with('approver')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        return view('leave-requests.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'leave_type' => ['required', 'in:izin,cuti,sakit'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'min:10', 'max:1000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:3072'],
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->storePublicly(
                'leave-attachments',
                ['disk' => config('jetstream.attachment_disk', 'public')]
            );
        }

        $data['user_id'] = Auth::id();
        $data['status'] = LeaveRequest::STATUS_PENDING;

        LeaveRequest::create($data);

        return redirect()->route('leave-requests.index')
            ->with('flash.banner', 'Pengajuan izin berhasil dikirim, menunggu persetujuan atasan.');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        abort_unless($leaveRequest->user_id === Auth::id(), 403);
        $leaveRequest->load('approver');
        return view('leave-requests.show', compact('leaveRequest'));
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        abort_unless($leaveRequest->user_id === Auth::id() && $leaveRequest->isPending, 403);
        $leaveRequest->delete();
        return redirect()->route('leave-requests.index')
            ->with('flash.banner', 'Pengajuan izin dibatalkan.');
    }
}
