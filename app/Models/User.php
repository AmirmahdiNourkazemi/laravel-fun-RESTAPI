<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Models\Companies;
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $fillable = ['type' , 'name' , 'email' , 'mobile' , 'national_code' , 'uuid' , 'is_admin'];
    public function companies()
    {
        return $this->hasMany(Companies::class);
    }
}
