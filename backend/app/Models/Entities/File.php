<?php
declare(strict_types=1);

namespace App\Models\Entities;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';
    protected $fillable = [
        'directory',
        'name',
        'mime_type',
        'extension',
        'size',
        'width',
        'height',
        'title',
        'comment',
        'visible',
    ];
    protected $casts = [
        'directory' => 'string',
        'name'      => 'string',
        'mime_type' => 'string',
        'extension' => 'string',
        'size'      => 'integer',
        'width'     => 'integer',
        'height'    => 'integer',
        'title'     => 'string',
        'comment'   => 'string',
        'visible'   => 'integer',
    ];

    public function getBasenameAttribute(): string
    {
        if (null == $this->name || null == $this->extension) {
            return '';
        }

        return $this->name . '.' . $this->extension;
    }

    public function getPathAttribute(): string
    {
        if (null == $this->basename || null == $this->directory) {
            return '';
        }

        return public_path() . $this->directory . '/' . $this->basename;
    }

    public function getUrlAttribute(): string
    {
        if (null == $this->basename || null == $this->directory) {
            return '';
        }

        return config('app.root_path') . $this->directory . '/' . $this->basename;
    }

    public function getFromPublicPathAttribute(): string
    {
        if (null == $this->basename || null == $this->directory) {
            return '';
        }

        return $this->directory . '/' . $this->basename;
    }

    public function exists(): bool
    {
        if (null == $this->basename || null == $this->directory) {
            return false;
        }

        return \File::exists($this->path);
    }
}
