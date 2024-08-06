<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FcVideo extends Model
{
    use HasFactory;

    protected $table = 'f_curve_videos';
    protected $fillable = [
        'max_score',
        'f_curve_step_id'
    ];

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function step()
    {
        return $this->belongsTo(FcStep::class, 'f_curve_step_id');
    }

    public function questions()
    {
        return $this->hasMany(DynamicQuestion::class, 'f_curve_video_id', 'id');
    }

    public function loadRelationShip()
    {
        return $this->load([
            'file' => function ($query) {
                $query->where('category', 'steps')
                    ->where('file_type', 'videos');
            }
        ])->loadCount('questions');
    }

}
