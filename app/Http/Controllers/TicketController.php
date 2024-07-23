<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TicketService;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function createTicket(Request $request)
    {
        $ticket = $this->ticketService->createTicket($request);
        return response()->json(['status' => 'E-ticket created successfully!', 'ticket' => $ticket], 201);
    }

    public function viewTickets()
    {
        $tickets = $this->ticketService->viewTickets();
        return response()->json($tickets);
    }

    public function getTicketDetailById($id)
    {
        $ticket = $this->ticketService->getTicketDetailById($id);
        return response()->json($ticket);
    }

    public function deleteTicket($id)
    {
        $result = $this->ticketService->deleteTicket($id);
        return response()->json($result);
    }


}
