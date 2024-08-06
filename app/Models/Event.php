<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Exam, Certification, User, Room};
use Carbon\Carbon;

class Event extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    use HasFactory;

    protected $table = 'events';
    protected $fillable = [
        'description',
        'type',
        'date',
        'active',
        'flg_test_exam',
        'flg_asist',
        'flg_survey_course',
        'flg_survey_evaluation',
        'exam_id',
        'min_score',
        'questions_qty',
        'test_exam_id',
        'elearning_id',
        'user_id',
        'responsable_id',
        'room_id',
        'security_id',
        'flg_security',
        'security_por_id',
        'flg_security_por',
        'owner_companies_id',
        'record_url',
        'course_module_id',
        'group_event_id'
    ];

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }

    public function course()
    {
        return $this->belongsToThrough(Course::class, Exam::class);
    }

    public function testExam()
    {
        return $this->belongsTo(Exam::class, 'test_exam_id', 'id');
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class, 'event_id', 'id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, Certification::class, 'event_id', 'user_id')
            ->withPivot(['evaluation_type'])->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id', 'id');
    }

    public function security()
    {
        return $this->belongsTo(User::class, 'security_id');
    }

    public function securityPor()
    {
        return $this->belongsTo(User::class, 'security_por_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function userSurveys()
    {
        return $this->hasMany(UserSurvey::class, 'event_id', 'id');
    }

    public function eLearning()
    {
        return $this->belongsTo(Elearning::class, 'elearning_id', 'id');
    }

    public function ownerCompany()
    {
        return $this->belongsTo(OwnerCompany::class, 'owner_companies_id', 'id');
    }

    public function courseModule()
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id', 'id');
    }

    public function specCourse()
    {
        return $this->belongsToThrough(SpecCourse::class, CourseModule::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'event_id');
    }




    public function loadCertificationsRelationships()
    {
        return $this->load([
            'certifications' => fn($query) =>
                $query->with('user.company')
                    ->withCount('evaluations'),
            'room'
        ]);
    }

    public function loadParticipantsRelationships()
    {
        return $this->load([
            'participants' => function ($query) {
                $query->where('certifications.evaluation_type', 'certification');
            }
        ]);
    }

    public function loadParticipantsCount()
    {
        return $this->loadCount(
            [
                'participants' => function ($query) {
                    $query->where('certifications.evaluation_type', 'certification');
                },
                'certifications as finished_certifications_count' => function ($query2) {
                    $query2->where('status', 'finished')
                        ->where('evaluation_type', 'certification');
                }
            ]
        );
    }

    public function loadCounts()
    {
        return $this->loadCount(['certifications', 'userSurveys']);
    }

    public function loadRelationships()
    {
        return $this->load([
            'user',
            'responsable',
            'exam' => fn($q) => $q->withCount(['questions' => fn($q) => $q->where('active', 'S')])
                ->withAvg(['questions' => fn($q) => $q->where('active', 'S')], 'points'),
            'course',
            'testExam',
            'ownerCompany',
            'room',
            'eLearning',
            'courseModule.specCourse',
            'groupEvent.participantGroups' => function ($query) {
                $query->where('active', 'S');
            }
        ])
            ->loadCount(['certifications', 'userSurveys']);
    }

    public function groupEvent()
    {
        return $this->belongsTo(GroupEvent::class, 'group_event_id', 'id');
    }


    // ACCESSORS AND MUTATORS

    public function getDateEsAttribute()
    {
        $date_carbon = Carbon::parse($this->date);
        $month_es = config('parameters.months_es')[$date_carbon->isoFormat('MM')];

        return $date_carbon->isoFormat('DD') . ' de ' . $month_es . ' del ' . $date_carbon->isoFormat('YYYY');
    }
}
