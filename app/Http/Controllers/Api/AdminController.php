<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\Ticket;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getComplaints()
    {
        $complaints = Complaint::all();
        return response()->json($complaints);
    }

    public function getTickets()
    {
        $tickets = Ticket::all();
        return response()->json($tickets);
    }

    public function sendTicket(Request $request)
    {
        $request->validate([
            'complaint_id' => 'required|exists:complaints,id',
            'description' => 'required',
            'status' => 'required|in:open,pending,closed',
        ]);

        $ticket = new Ticket();
        $ticket->complaint_id = $request->complaint_id;
        $ticket->date_sent = now();
        $ticket->description = $request->description;
        $ticket->status = $request->status;
        $ticket->save();


        return response()->json(['status' => 'E-ticket created successfully!', 'ticket' => $ticket]);
    }
}
