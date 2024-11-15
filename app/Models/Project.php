<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
class Project extends Model
{
    use HasUuid ,HasApiTokens, Notifiable, softDeletes;
    protected $guarded = [];
}
