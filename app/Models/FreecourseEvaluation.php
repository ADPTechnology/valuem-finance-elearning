<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreecourseEvaluation extends Model
{
    use HasFactory;

    protected $table = 'freecourse_evaluations';

    protected $fillable = [
        'course_section_id',
        'exam_id',
        'title',
        'description',
        'value',
        'active'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function userEvaluations()
    {
        return $this->belongsToMany(ProductCertification::class, 'user_fc_evaluations', 'fc_evaluation_id', 'p_certification_id')
            ->withPivot(['notes', 'points', 'status'])->withTimestamps();
    }

    public function userFcEvaluations()
    {
        return $this->hasMany(UserFcEvaluation::class, 'fc_evaluation_id');
    }
}
