<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webinar extends Model
{
    use HasFactory;

    protected $table = 'webinars';

    protected $fillable = [
        'title',
        'description',
        'date',
        'active'
    ];

    public function events()
    {
        return $this->hasMany(WebinarEvent::class, 'webinar_id');
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    
    public function loadImage()
    {
        return $this->load([
            'file' => fn ($query) =>
            $query->where('file_type', 'imagenes')
        ]);
    }
}
