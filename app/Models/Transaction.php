<?php

namespace App\Models;

use App\Traits\HasUuid;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasUuid;
    protected $guarded = [];

    const TYPES = [
        'buy' => 1,
        'sell' => 2,
        'profit' => 3,
        'deposit' => 4,
        'withdraw' => 5,
        'referal' => 6,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
