<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketService
{
    public function createTicket(Request $request)
    {
        $request->validate([
            'complaint_id' => 'required|exists:complaints,id',
            'description' => 'required|string',
            'status' => 'required|in:open,pending,closed',
        ]);

        $ticket = new Ticket();
        $ticket->complaint_id = $request->complaint_id;
        $ticket->date_sent = now();
        $ticket->description = $request->description;
        $ticket->status = $request->status;
        $ticket->save();

        return $ticket;
    }

    public function viewTickets()
    {
        return Ticket::all();
    }
}
