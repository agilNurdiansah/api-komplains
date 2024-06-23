<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{
    protected $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of admin users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdminUsers()
    {
        $admins = $this->userService->getAdminUsers();

        return response()->json($admins);
    }

    /**
     * Display a listing of Customer users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerUsers()
    {
        $customers = $this->userService->getCustomerUser();

        return response()->json($customers);
    }

     /**
     * Get the count of admin users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(): JsonResponse
    {
        $adminUserCount = $this->userService->countAdminUsers();
        return response()->json(['admin_count' => $adminUserCount]);
    }

     /**
     * Get the total count of users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function countAllUsers(): JsonResponse
    {
        $userCount = $this->userService->countAllUsers();
        return response()->json(['total_user_count' => $userCount]);
    }
}
