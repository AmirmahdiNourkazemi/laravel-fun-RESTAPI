<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Project extends Model implements HasMedia
{
    use HasUuid ,HasApiTokens, Notifiable, softDeletes ,InteractsWithMedia;
    protected $guarded = [];
    protected $appends = ['images'];
    protected $hidden = ['media'];
    protected $with = ['media'];
    protected $casts = [
        'finish_at' => 'datetime',
        'start_at' => 'datetime',
        'properties' => 'array',
        'time_table' => 'array',
    ];
    public function getImagesAttribute()
    {
        return $this->media->where('collection_name', 'images')->map->only(['uuid', 'original_url', 'name', 'collection_name'])->values();
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_project')->withPivot(['amount', 'id', 'public', 'trace_code'])->withTimestamps();
    }
    public function buy($amount, $user, $public = false, $refId, $inviterId = null)
    {
        if ($userWithPivot = $this->users()->where('users.id', $user->id)->first()) {
            $this->users()->updateExistingPivot($user->id, [
                'amount' => $amount + $userWithPivot->pivot->amount,
                'public' => $public
            ]);
        } else {
            $this->users()->attach($user->id, ['amount' => $amount, 'public' => $public]);
        }

        $transaction = Transaction::create([
            'project_id' => $this->id,
            'user_id' => $user->id,
            'inviter_id' => $inviterId,
            'amount' => $amount,
            'type' => Transaction::TYPES['buy'],
        ]);

        $this->increment('fund_achieved', $amount);

        // $response = IFBApi::sendFinancingProvider($this->ifb_uuid, $user, $amount, $refId ?? rand(10000, 99999));

       
        return response()->json([
            $transaction
        ]);

            // $transaction->trace_code = $response['trace_code'];
            $transaction->save();

            // $this->users()->updateExistingPivot($user->id, [
            //     'trace_code' => $response['trace_code']
            // ]);
        
    }

}
