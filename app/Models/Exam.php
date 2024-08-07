<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    DynamicQuestion,
    Event,
    Course,
    OwnerCompany
};

class Exam extends Model
{
    use HasFactory;

    protected $table = 'exams';
    protected $fillable = [
        'title',
        'exam_time',
        'course_id',
        'owner_company_id',
        'active',
        'exam_type'
    ];

    public function questions()
    {
        return $this->hasMany(DynamicQuestion::class, 'exam_id', 'id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function fcEvaluations()
    {
        return $this->hasMany(FreecourseEvaluation::class, 'exam_id');
    }

    public function loadRelationships()
    {
        return $this->load(['ownerCompany:id,name', 'course:id,description'])
            ->loadCount([
                'questions' => function ($q) {
                    $q->where('active', 'S');
                },
                'events'
            ])
            ->loadSum([
                'questions' => function ($q2) {
                    $q2->where('active', 'S');
                }
            ], 'points');
    }
    public function loadRelationshipsStep()
    {
        return $this->load(['fcStep', 'fcStep.instance', 'fcStep.instance.forgettingCurve'])
            ->loadCount([
                'questions' => function ($q) {
                    $q->where('active', 'S');
                },
            ])
            ->loadSum([
                'questions' => function ($q2) {
                    $q2->where('active', 'S');
                }
            ], 'points');
    }

}
