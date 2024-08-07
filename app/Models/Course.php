<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    Folder,
    Exam,
    CourseCategory,
    CourseSection,
    SectionChapter,
    File,
    Certification
};

class Course extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    use HasFactory;

    protected $table = 'courses';
    protected $fillable = [
        'description',
        'subtitle',
        'date',
        'hours',
        'time_start',
        'time_end',
        'active',
        'course_type',
        'flg_public',
        'flg_recom',
        'min_score',
        'category_id',
        'course_type_id'
    ];

    public function folders()
    {
        return $this->hasMany(Folder::class, 'id_course', 'id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'course_id', 'id');
    }

    public function courseCategory()
    {
        return $this->belongsTo(CourseCategory::class, 'category_id', 'id');
    }

    public function courseSections()
    {
        return $this->hasMany(CourseSection::class, 'course_id', 'id');
    }

    public function courseChapters()
    {
        return $this->hasManyThrough(SectionChapter::class, CourseSection::class, 'course_id', 'section_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    // ----------- ORDERS ---------

    public function orders()
    {
        return $this->morphToMany(Order::class, 'orderable', 'order_details')
            ->withPivot(['quantity', 'unit_price'])->withTimestamps();
    }

    // ------------- USER CERTIFICATIONS ---------

    public function userProductCertifications()
    {
        return $this->morphToMany(User::class, 'certificable', 'product_certifications')
            ->withPivot(['id', 'flg_finished', 'status', 'score'])->withTimestamps();
    }

    public function productCertifications()
    {
        return $this->morphMany(ProductCertification::class, 'certificable');
    }


    // --------------- FREECOURSES -------------

    public function courseEvaluations()
    {
        return $this->hasManyThrough(FreecourseEvaluation::class, CourseSection::class, 'course_id', 'course_section_id',);
    }

    public function freecourseDetail()
    {
        return $this->hasOne(FreecourseDetail::class, 'course_id');
    }


    public function loadCourseImage()
    {
        return $this->load([
            'file' => fn ($query) =>
            $query->where('file_type', 'imagenes')
                ->where('category', 'cursos')
        ]);
    }

    public function loadFreeCourseImage()
    {
        return $this->load([
            'file' => fn ($query2) =>
            $query2->where('file_type', 'imagenes')
                ->where('category', 'cursoslibres')
        ]);
    }


    public function loadFreeCourseRelationships()
    {
        return $this->load(
            [
                'courseEvaluations' => fn ($q) =>
                    $q->select(
                        'freecourse_evaluations.id',
                        'freecourse_evaluations.exam_id',
                        'freecourse_evaluations.course_section_id')
                        ->with('exam:id,exam_time'),
                'courseCategory',
                'courseSections' => fn ($query) =>
                $query->orderBy('section_order', 'ASC')
                    ->withCount('sectionChapters'),
                'file' => fn ($query2) =>
                $query2->where('file_type', 'imagenes')
                    ->where('category', 'cursoslibres')
            ]
        )->loadCount(['courseSections', 'courseChapters'])
            ->loadSum('courseChapters', 'duration');
    }

    public function getSectionLastOrder()
    {
        $this->loadMax('courseSections', 'section_order');

        return $this->course_sections_max_section_order ?? 0;
    }

    public function getExamsDurationAttribute()
    {
        $duration = $this->courseEvaluations->sum(function ($evaluation) {
            return $evaluation->exam->exam_time ?? 0;
        });

        return $duration ?? 0;
    }
}
