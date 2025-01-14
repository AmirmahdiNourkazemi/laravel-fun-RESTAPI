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
    protected $casts = [
        'type' => 'boolean',
        'is_admin' => 'boolean',
    ];
    public function companies()
    {
        return $this->hasMany(Companies::class);
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'user_project')->withPivot(['amount', 'id', 'public', 'trace_code'])->withTimestamps();
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_project')->withPivot(['amount', 'id', 'public', 'trace_code'])->withTimestamps();
    }
    public function firstProject()
    {
        return $this->belongsToMany(Project::class, 'user_project')->withPivot(['amount', 'id', 'public', 'trace_code'])->withTimestamps()->orderBy('user_project.created_at');
    }
    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }
}
