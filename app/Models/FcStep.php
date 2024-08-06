<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class FcStep extends Model
{
    use HasFactory;

    protected $table = 'f_curve_steps';
    protected $fillable = [
        'title',
        'description',
        'type',
        'order',
        'active',
        'f_curve_instance_id'
    ];

    public function forgettingCurve()
    {
        return $this->belongsTo(ForgettingCurve::class, 'forgetting_curve_id');
    }

    public function instance()
    {
        return $this->belongsTo(FcInstance::class, 'f_curve_instance_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'f_curve_step_id');
    }

    public function video()
    {
        return $this->hasOne(FcVideo::class, 'f_curve_step_id');
    }

    public function certifications()
    {
        return $this->belongsToMany(Certification::class, 'f_curve_step_progress', 'f_curve_step_id', 'certification_id')
            ->withPivot(['id', 'status', 'score'])->withTimestamps();
    }

    public function fcStepProgress()
    {
        return $this->hasMany(FcStepProgress::class, 'f_curve_step_id');
    }

    public function loadRelationShipExam()
    {
        return $this->load([
            'instance.forgettingCurve.courses',
            'instance.forgettingCurve',
            'instance',
            'exams' => function ($query) {
                $query->with('questions')->withCount('questions');
            },
        ])->loadCount(['exams']);
    }

    public function loadRelationShipVideo()
    {
        return $this->load([
            'instance.forgettingCurve.courses',
            'instance.forgettingCurve',
            'instance',
            'video' => function ($query) {
                $query->withCount('questions')
                    ->with([
                        'questions',
                        'file' => function ($query) {
                            $query->where('file_type', 'videos')
                                ->where('category', 'steps');
                        },
                    ]);
            }
        ])->loadCount(['video']);

    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function loadImage()
    {
        return $this->load([
            'file' => function ($query) {
                $query->where('file_type', 'imagenes')
                    ->where('category', 'pasos');
            }
        ]);
    }

}
