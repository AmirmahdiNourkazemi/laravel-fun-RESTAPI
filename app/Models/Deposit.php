<?php

namespace App\Models;
use App\Traits\HasUuid;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Deposit extends Model implements HasMedia
{
    use HasUuid, InteractsWithMedia;

    protected $guarded = [];
    protected $appends = ['image'];
    protected $hidden = ['media'];
    protected $with = ['media'];

    const STATUSES = [
        'pending' => 1,
        'success' => 2,
        'rejected' => 3,
    ];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function getImageAttribute()
    {
        return $this->media->where('collection_name', 'image')->first()?->only(['uuid', 'original_url', 'name', 'collection_name']);
    }

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
