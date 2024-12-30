<?php

namespace App\Models;
use App\Traits\HasUuid;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasUuid;
    
    protected $guarded = [];
    const STATUSES = [
        'pending' => 1,
        'success' => 2,
        'consumed' => 3,
        'failed' => 4,
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
