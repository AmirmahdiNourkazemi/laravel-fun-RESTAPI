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
    public function getImagesAttribute()
    {
        return $this->media->where('collection_name', 'images')->map->only(['uuid', 'original_url', 'name', 'collection_name'])->values();
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

}
