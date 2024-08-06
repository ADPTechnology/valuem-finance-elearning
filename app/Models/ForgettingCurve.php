<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForgettingCurve extends Model
{
    use HasFactory;

    protected $table = 'forgetting_curves';
    protected $fillable = [
        'title',
        'description',
        'min_score',
        'active'
    ];


    public function instances()
    {
        return $this->hasMany(FcInstance::class, 'forgetting_curve_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'fc_courses', 'fcurve_id', 'course_id')->withTimestamps();
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
            'file' => fn($query) =>
                $query->where('file_type', 'imagenes')
        ]);
    }

    public function loadRelationShips()
    {
        $this->load([
            'courses',
            'instances',
            'instances.steps' => function ($q) {
                $q->withCount('fcStepProgress', 'exams', 'video')->with([
                    'video' => function ($q1) {
                        $q1->withCount('questions');
                    },
                    'exams' => function ($q) {
                        $q->withCount('questions');
                    }
                ]);
            }
        ])->loadCount('instances');
    }

}
