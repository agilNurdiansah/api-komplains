<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

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
}
