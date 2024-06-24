<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ComplaintService;

class ComplaintController extends Controller
{
    protected $complaintService;

    public function __construct(ComplaintService $complaintService)
    {
        $this->complaintService = $complaintService;
    }

    public function createComplaint(Request $request)
    {
        $complaint = $this->complaintService->createComplaint($request);
        return response()->json([
            'status' => 'Complaint filed successfully!',
            'complaint' => $complaint,
        ], 201);
    }

    public function getComplaintByUserNameAndId(Request $request, $id)
    {
        $username = $request->input('username');
        $complaint = $this->complaintService->getComplaintByUserNameAndId($username, $id);
        return response()->json($complaint);
    }

    public function viewComplaints()
    {
        $complaints = $this->complaintService->viewComplaints();
        return response()->json($complaints);
    }

    public function viewComplaintsByUserId()
    {
        $complaints = $this->complaintService->getComplaintsByUserId();
        return response()->json($complaints);
    }


    public function updateComplaintAndTicketStatus(Request $request, $id)
    {
        $result = $this->complaintService->updateComplaintAndTicketStatus($request, $id);
        return response()->json([
            'status' => 'Complaint and Ticket status updated successfully!',
            'complaint' => $result['complaint'],
            'ticket' => $result['ticket'],
        ]);
    }

}
