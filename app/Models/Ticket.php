<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'ticket_id',
        'date_sent',
        'description',
        'status',
    ];

    // Define the relationship with Complaint model
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
