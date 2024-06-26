<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketCreated;
use App\Mail\TicketUpdated;
use Illuminate\Support\Facades\Storage;


class ComplaintService
{
    public function createComplaint(Request $request)
{
    $request->validate([
        'username' => 'required|string|exists:users,username',
        'description' => 'required|string',
        'evidence' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mkv|max:20480',
    ]);

    $user = User::where('username', $request->input('username'))->firstOrFail();

    $complaint = new Complaint();
    $complaint->user_id = $user->id;
    $complaint->date_filed = now();
    $complaint->description = $request->input('description');

    if ($request->hasFile('evidence')) {
        $file = $request->file('evidence');
        $fileName = $file->getClientOriginalName();
        $filePath = 'public/';

        // Store the file on disk
        $storedPath = Storage::putFileAs($filePath, $file, $fileName);

        // Save the stored path to the complaint model
        $complaint->evidence = Storage::url($storedPath);
    }

    $complaint->status = 'open';
    $complaint->save();

    return $complaint;
}

    public function getComplaintByUserNameAndId($username, $id)
    {
        $user = User::where('username', $username)->firstOrFail();
        $complaint = Complaint::where('user_id', $user->id)
                              ->where('id', $id)
                              ->firstOrFail();

        return $complaint;
    }

    public function viewComplaints($perPage)
    {
        return Complaint::paginate($perPage);
    }

    public function updateComplaintAndTicketStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,pending,closed',
        ]);

        $status = $request->input('status');

        $complaint = Complaint::findOrFail($id);

        $user = User::findOrFail($complaint->user_id);

        // Update the complaint status
        $complaint->status = $status;
        $complaint->save();

        // Update the ticket status associated with the complaint
        $ticket = Ticket::where('complaint_id', $complaint->id)->firstOrFail();
        $ticket->status = $status;
        $ticket->save();

        Mail::to($user->email)->send(new TicketUpdated($ticket));

        return [
            'complaint' => $complaint,
            'ticket' => $ticket,
        ];
    }

    public function getComplaintsByUserId(Request $request, $userId, $perPage, $sortBy = 'created_at', $sortOrder = 'asc')
    {
        $sortOrder = strtolower($sortOrder) === 'desc' ? 'desc' : 'asc';

        return Complaint::where('user_id', $userId)
                        ->orderBy($sortBy, $sortOrder)
                        ->paginate($perPage);
    }


    public function getComplaintDetailById($id)
    {
        return Complaint::with(['user', 'tickets'])->findOrFail($id);
    }
}
