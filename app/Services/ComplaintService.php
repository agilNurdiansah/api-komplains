<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


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
            $complaint->evidence = $request->file('evidence')->store('evidences');
        }

        $complaint->status = 'open';
        $complaint->save();

        // Create a ticket associated with this complaint
        $ticket = new Ticket();
        $ticket->complaint_id = $complaint->id;
        $ticket->date_sent = now();
        $ticket->description = $complaint->description;
        $ticket->status = 'open';
        $ticket->save();

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

    public function viewComplaints()
    {
        return Complaint::all();
    }


    public function updateComplaintAndTicketStatus(Request $request, $id)
    {
        $request->validate([
           'status' => 'required|in:open,pending,closed',
        ]);

        $status = $request->input('status');

        // Update the complaint status
        $complaint = Complaint::findOrFail($id);
        $complaint->status = $status;
        $complaint->save();

        // Update the ticket status associated with the complaint
        $ticket = Ticket::where('complaint_id', $complaint->id)->firstOrFail();
        $ticket->status = $status;
        $ticket->save();

        return [
            'complaint' => $complaint,
            'ticket' => $ticket,
        ];
    }

    public function getComplaintsByUserId()
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        $complaints = Complaint::where('user_id', $userId)->get();

        return $complaints;
    }


}
