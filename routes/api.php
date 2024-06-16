<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\TicketController;

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


Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/complaints', [AdminController::class, 'getComplaints']);
        Route::post('/admin/complaints', [ComplaintController::class, 'createComplaint'])->name('complaints.create');
        Route::post('/admin/complaints/update/{id}', [ComplaintController::class, 'updateComplaintAndTicketStatus'])->name('complaints.update');

        Route::get('/admin/tickets', [AdminController::class, 'getTickets']);
        Route::post('/admin/tickets', [AdminController::class, 'sendTicket']);
    });

    Route::middleware('role:customer')->group(function () {
        Route::get('/customer/complaints', [ComplaintController::class, 'viewComplaintsByUserId'])->name('complaints.index');
        Route::get('/customer/tickets', [TicketController::class, 'viewTickets'])->name('tickets.index');
    });
});

