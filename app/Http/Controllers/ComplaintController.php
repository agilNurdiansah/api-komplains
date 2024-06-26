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
        $perPage = $request->input('per_page', 5);
        $complaints = $this->complaintService->viewComplaints($perPage);
        return response()->json($complaints);
    }

    public function viewComplaintsByUserId(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'asc');

        // Validasi sortOrder agar hanya menerima 'asc' atau 'desc'
        $sortOrder = strtolower($sortOrder) === 'desc' ? 'desc' : 'asc';

        $complaints = $this->complaintService
                           ->getComplaintsByUserId($request, $request->user()->id, $perPage, $sortBy, $sortOrder);

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
            // Remove 'storage/' from the path if it exists
            $evidencePath = str_replace('storage/', '', $complaint->evidence);
            $complaint->evidence_url = $baseImageUrl . '/' . $evidencePath;
        } else {
            $complaint->evidence_url = null;
        }

        return response()->json($complaint);
    }


}
