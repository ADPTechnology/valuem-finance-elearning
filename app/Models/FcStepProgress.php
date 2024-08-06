<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FcStepProgress extends Model
{
    use HasFactory;

    protected $table = 'f_curve_step_progress';
    protected $fillable = [
        'status',
        'score',
        'certification_id',
        'f_curve_step_id'
    ];

    public function step()
    {
        return $this->belongsTo(FcStep::class, 'f_curve_step_id');
    }

    public function certification()
    {
        return $this->belongsTo(Certification::class, 'certification_id');
    }

    // public function videoProgress()
    // {
    //     return $this->belongsToMany(FcVideoQuestion::class, 'f_curve_video_progress', 'f_curve_step_progress_id', 'f_curve_video_question_id')
    //         ->withPivot(['id', 'answer', 'is_correct'])->withTimestamps();
    // }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'f_curve_step_progress_id');
    }

    public function loadRelationShip()
    {
        return $this->load([
            'evaluations'
        ]);
    }

}
