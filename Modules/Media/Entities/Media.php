<?php

namespace Modules\Media\Entities;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'path',
        'thumb',
        'meduim',
        'large',
        'type', // photo, video or file
        'title', // nullable
        'description', // nullable
        'is_main'
    ];

    protected $casts = [
        'is_main' => 'boolean'
    ];

    public function mediable()
    {
        return $this->morphTo();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    public function getPathAttribute($value)
    {
        if (!Str::startsWith($value, 'http')) {
            $value = str_replace('public', 'storage', $value);
        }
        return $value;
    }

    public function getThumbAttribute($value)
    {
        if (!Str::startsWith($value, 'http')) {
            $value = str_replace('public', 'storage', $value);
        }
        return $value;
    }

    public function getMeduimAttribute($value)
    {
        if (!Str::startsWith($value, 'http')) {
            $value = str_replace('public', 'storage', $value);
        }
        return $value;
    }

    public function getLargeAttribute($value)
    {
        if (!Str::startsWith($value, 'http')) {
            $value = str_replace('public', 'storage', $value);
        }
        return $value;
    }

    // public function scopeMain($query, $type)
    // {
    //     return $query->where('is_main', 1)->where('type', $type);
    // }

    // protected function getIsMainAttribute($value)
    // {
    //     return $value > 0 ? true : false;
    // }

    protected function getVideoIdAttribute($value)
    {
        return getYoutubeVideoId($this->path);
    }

    public function deleteMedia()
    {
        $this->deleteOldMediaPath()->delete();
        return $this;
    }

    private function deleteOldMediaPath()
    {
        Storage::delete($this->path);
        return $this;
    }
}
