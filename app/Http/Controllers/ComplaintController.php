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

    public function viewComplaints(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $complaints = $this->complaintService->viewComplaints($perPage);
        return response()->json($complaints);
    }

    public function viewComplaintsByUserId(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $complaints = $this->complaintService->getComplaintsByUserId($request->user()->id, $perPage);
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

     /**
     * Get detailed complaint by ID including user and tickets.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComplaintById($id)
    {
        $complaint = $this->complaintService->getComplaintDetailById($id);

        $baseImageUrl = url('storage');

        if (!empty($complaint->evidence)) {
            $complaint->evidence_url = $baseImageUrl . '/' . str_replace('evidences/', '', $complaint->evidence);
        } else {
            $complaint->evidence_url = null;
        }

        return response()->json($complaint);
    }

}
