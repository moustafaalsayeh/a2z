<?php

namespace Modules\Media\Helpers;

use ReflectionClass;
use Illuminate\Support\Str;
use Modules\Media\Entities\Media;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait Mediable
{
    // Creates the relation with Media Model + Filteration by media file_type and media title
    public function media($type = null, $title = null)
    {
        return $this->morphMany(Media::class, 'mediable')
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($title, function ($query) use ($title) {
                $query->where('title', $title);
            })
            ->select([
                'id',
                'type',
                'mediable_type',
                'mediable_id',
                'path',
                'thumb',
                'meduim',
                'large',
                'title',
                'description',
                'is_main'
            ]);
    }

    // Creates the relation with Media Model (One-to-one) + Filteration by media file_type
    public function singleMedia($type = null)
    {
        return $this->morphOne(Media::class, 'mediable')
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->select([
                'id',
                'type',
                'mediable_type',
                'mediable_id',
                'path',
                'thumb',
                'meduim',
                'large',
                'title',
                'description',
                'is_main'
            ]);
    }

    // Old version functions
    public function getPhotosDirectoryAttribute($value)
    {
        $className = new ReflectionClass($this);
        return Str::plural(strtolower($className->getShortName()));
    }

    // Old version functions
    public function getFilesDirectoryAttribute($value)
    {
        $className = new ReflectionClass($this);
        return Str::plural(strtolower($className->getShortName()));
    }

    // Old version functions
    public function getVideosDirectoryAttribute($value)
    {
        $className = new ReflectionClass($this);
        return Str::plural(strtolower($className->getShortName()));
    }

    // Add new media record for this model
    public function addMedia($type, $path, $thumb, $meduim, $large, $title = null, $description = null, $isMain = false)
    {
        !$isMain ?: $this->normalizePreviousMainMedia();

        $this->media()->create([
            'path' => $path,
            'thumb' => $thumb,
            'meduim' => $meduim,
            'large' => $large,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'is_main' => (bool) $isMain
        ]);
        return $this;
    }

    // Updates a media instance
    public function replaceMedia(Media $singleMedia, $path = null, $title = null, $description = null, $isMain = false)
    {
        $oldPath = $path == null ?: $singleMedia->path;
        $singleMedia->is_main || (!$singleMedia->is_main && !$isMain) ?: $this->normalizePreviousMainMedia();

        $singleMedia->update([
            'path' => $path ?? $singleMedia->path,
            'title' => $title ?? $singleMedia->title,
            'description' => $description ?? $singleMedia->description,
            'is_main' => (bool) $isMain
        ]);
        !$oldPath ?: $this->deleteOldMediaPath($oldPath);
    }

    // Delete a media instance
    // Don't use it, use delete on Media model directly
    public function deleteMedia(Media $singleMedia)
    {
        $this->deleteOldMediaPath($singleMedia->path);
        $this->deleteOldMediaPath($singleMedia->thumb);
        $this->deleteOldMediaPath($singleMedia->meduim);
        $this->deleteOldMediaPath($singleMedia->large);
        $singleMedia->delete();
    }

    // Delete all media of this Model
    public function deleteAllMedia()
    {
        $this->media->each(function ($singleMedia) {
            $this->deleteMedia($singleMedia);
        });
    }

    // Store the given file to the filesystem + Create new Media record
    public function addMediaByType($type, $requestFile, $name = null, string $title = null, $description = null, $isMain = false)
    {
        $typeDirectory = Str::plural($type) . 'Directory';
        // $name = $name ?? $requestFile->getClientOriginalName();
        $name = $name ? $name . uniqid() : uniqid(); // dont't store file original name
        $original_image = $requestFile->storeAs(
                config('media.' . Str::plural($type) . '.path') . $this->$typeDirectory,
                $name . '.' . $requestFile->getClientOriginalExtension()
        );
        $thumb_image = $requestFile->storeAs(
                config('media.' . Str::plural($type) . '.path') . $this->$typeDirectory,
                $name . '_thumb_' . '.' . $requestFile->getClientOriginalExtension()
        );
        $meduim_image = $requestFile->storeAs(
                config('media.' . Str::plural($type) . '.path') . $this->$typeDirectory,
                $name . '_meduim_' . '.' . $requestFile->getClientOriginalExtension()
        );
        $large_image = $requestFile->storeAs(
                config('media.' . Str::plural($type) . '.path') . $this->$typeDirectory,
                $name . '_large_' . '.' . $requestFile->getClientOriginalExtension()
        );

        $media = $this->addMedia(
            $type,
            $original_image,
            $thumb_image,
            $meduim_image,
            $large_image,
            $title,
            $description,
            $isMain
        );

        $this->createThumbnail(str_replace('public', 'storage', $thumb_image), 256, 256);
        $this->createThumbnail(str_replace('public', 'storage', $meduim_image), 640, 640);
        $this->createThumbnail(str_replace('public', 'storage', $large_image), 1200, 720);

        return $media;
    }

    /**
     * Create a thumbnail of specified size
     *
     * @param string $path path of thumbnail
     * @param int $width
     * @param int $height
     */
    public function createThumbnail($path, $width, $height)
    {
        $img = Image::make($path)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($path);
    }

    // Edit given Media instance by uploading new file to it
    public function editMediaByType(Media $singleMedia, $requestFile, $name, string $title)
    {
        $typeDirectory = Str::plural($singleMedia->type) . 'Directory';
        return $this->editMedia(
            $singleMedia,
            $requestFile->storeAs(
                config('media.' . Str::plural($singleMedia->type) . '.path') . $this->$typeDirectory,
                $name . uniqid() . '.' . $requestFile->getClientOriginalExtension()
            ),
            $title
        );
    }

    // Delete all media of the given file_type for this Model
    public function deleteAllMediaType($type)
    {
        $this->media->where('type', $type)->each(function ($singleMedia) {
            $this->deleteMedia($singleMedia);
        });
    }

    // Deletes the file from the filesystem
    protected function deleteOldMediaPath($path)
    {
        Storage::delete($path);
    }

    // make the current mainMedia not main
    protected function normalizePreviousMainMedia()
    {
        if ((bool) optional($this->mainMedia)->is_main) {
            $this->mainMedia->update([
                'is_main' => false
            ]);
        }
        return;
    }

    // return the Logo of this Model
    public function getLogoMediaAttribute()
    {
        $logo = $this->media()->where('title', 'logo')->first();
        return $logo;
    }

    // return the MainMedia of this Model
    public function getMainMediaAttribute()
    {
        $main = $this->media()->where('is_main', true)->first();
        return $main ? $main : $this->media->first();
    }

    // Return if the singleMedia (in case of one-to-one relation) ismain or not
    public function getIsMainMediaAttribute()
    {
        return (bool) $this->singleMedia->is_main;
    }

    // Get photo attribute for all Model's media
    public function getPhotosAttribute()
    {
        return $this->media('photo')->get();
    }

    // Get first photo attribute for Model's media
    public function getPhotoAttribute()
    {
        return $this->media('photo')->first();
    }

    public function getFilesAttribute()
    {
        return $this->media('file')->get();
    }

    public function getFileAttribute()
    {
        return $this->media('file')->first();
    }

    public function FileWithTitle($title)
    {
        return $this->media('file')->where('title', $title)->first();
    }

    public function FilesWithTitle($title)
    {
        return $this->media('file')->where('title', $title)->get();
    }

    public function getVideosAttribute()
    {
        return $this->media('video')->get();
    }

    public function getVideoAttribute()
    {
        return $this->media('video')->first();
    }

    protected function getVideoIdAttribute($value)
    {
        return getYoutubeVideoId($this->path);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->deleteAllMedia();
        });
    }
}
