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

    public function getComplaints(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $complaints = Complaint::paginate($perPage);
        return response()->json($complaints);
    }

    public function getTickets(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $tickets = Ticket::paginate($perPage);
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
