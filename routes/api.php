<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/admins/count', [UserController::class, 'count']);
Route::get('/users/count', [UserController::class, 'countAllUsers']);
Route::get('/users/customers/count', [UserController::class, 'countCustomers']);
Route::get('/admin-users', [UserController::class, 'getAdminUsers']);
Route::get('/detail/complaints/{id}', [ComplaintController::class, 'getComplaintById'])->name('complaints.detail');




Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/complaints', [AdminController::class, 'getComplaints']);
        Route::post('/admin/complaints', [ComplaintController::class, 'createComplaint'])->name('complaints.create');
        Route::post('/admin/complaints/update/{id}', [ComplaintController::class, 'updateComplaintAndTicketStatus'])->name('complaints.update');

        Route::get('/customers-users', [UserController::class, 'getCustomerUsers']);
        Route::get('/admin/tickets', [AdminController::class, 'getTickets']);
        Route::post('/admin/tickets', [TicketController::class, 'createTicket']);
        Route::get('/admin/detail/tickets/{id}', [TicketController::class, 'getTicketDetailById'])->name('tickets.detail');

    });

    Route::middleware('role:customer')->group(function () {
        Route::get('/customer/complaints', [ComplaintController::class, 'viewComplaintsByUserId'])->name('complaints.index');
        Route::get('/customer/tickets', [TicketController::class, 'viewTickets'])->name('tickets.index');
    });

});
