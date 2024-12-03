<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Ticket extends Model
{
    use HasUuid;

    protected $guarded = [];

    const CATEGORIES = [
        'tech_issue' => 1,
        'question' => 2,
        'violation' => 3,
        'other' => 99
    ];

    const STATUSES = [
        'open' => 1,
        'answered' => 2,
        'closed' => 3
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }


    
}
