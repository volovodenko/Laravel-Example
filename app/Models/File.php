<?php

namespace App\Models;

use App\Enums\FileStorage;
use App\Models\Contracts\File as FileContract;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\File.
 *
 * @property int                             $id
 * @property string                          $file_name
 * @property null|string                     $original_name
 * @property string                          $storage
 * @property string                          $directory
 * @property string                          $mime_type
 * @property int                             $size
 * @property mixed                           $extension
 * @property bool                            $is_image
 * @property mixed                           $url
 * @property null|\Illuminate\Support\Carbon $created_at
 * @property null|\Illuminate\Support\Carbon $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereDirectory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereStorage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File query()
 * @mixin \Eloquent
 */
class File extends Model implements FileContract
{
    protected $fillable = [
        'file_name',
        'original_name',
        'storage',
        'directory',
        'mime_type',
        'size',
    ];

    public function path(): string
    {
        return \Str::finish($this->directory, '/') . $this->file_name;
    }

    public function fullPath(): string
    {
        return \Storage::disk($this->storage())->path($this->path());
    }

    public function storage(): string
    {
        return $this->storage;
    }

    public function fileName(): string
    {
        return $this->file_name;
    }

    public function storageAsEnum(): FileStorage
    {
        return new FileStorage($this->attributes['storage']);
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'image');
    }

    public function getExtensionAttribute()
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    public function getUrlAttribute()
    {
        return $this->storageAsEnum()->equals(FileStorage::LOCAL())
            ? route('api.files.get', [
                'id'    => $this->id,
                'token' => \JWT::getToken($this),
            ])
            : \Storage::disk($this->storage())->url($this->path());
    }
}
