<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketCreated;
use App\Models\User;
use App\Models\Complaint;
use Illuminate\Support\Facades\Log;

class TicketService
{
    public function createTicket(Request $request)
    {
        try {
            $request->validate([
                'complaint_id' => 'required|exists:complaints,id',
                'description' => 'required|string',
                'status' => 'required|in:open,pending,closed',
            ]);

            $complaint = Complaint::findOrFail($request->complaint_id);

            $ticket = new Ticket();
            $ticket->complaint_id = $complaint->id;
            $ticket->date_sent = now();
            $ticket->description = $request->description;
            $ticket->status = $request->status;
            $ticket->save();

            // Mendapatkan objek user terkait dengan keluhan
            $user = $complaint->user;

            // Mengirim email ke user
            Mail::to($user->email)->send(new TicketCreated($ticket));

            // Mengembalikan response yang sesuai
            return [
                'status' => 'E-ticket created successfully!',
                'ticket' => $ticket,
            ];
        } catch (\Exception $e) {
            // Tangani exception di sini
            Log::error('Gagal mengirim email: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengirim email'], 500);
        }
    }

    public function viewTickets()
    {
        return Ticket::all();
    }
}
