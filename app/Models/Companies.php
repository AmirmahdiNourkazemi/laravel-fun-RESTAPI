<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasUuid;
use DateTimeInterface;


class Companies extends Model
{
    use HasUuid ,HasApiTokens, HasFactory, Notifiable ,Filterable;
    protected $guarded = [];
    const FUNDS = [
        'up_to_2' => 1,
        '2_to_5' => 2,
        '5_to_10' => 3,
        'more_than_10' => 4,
    ];

    const INCOMES = [
        'up_to_5' => 1,
        '5_to_10' => 2,
        '10_to_20' => 3,
        'more_than_20' => 4,
    ];

    const PROFITS = [
        'negative' => 1,
        'positive' => 2
    ];

    const BOUNCED_CHECK_STATUSES = [
        'none' => 1,
        'done' => 2,
        'in_progress' => 3
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
