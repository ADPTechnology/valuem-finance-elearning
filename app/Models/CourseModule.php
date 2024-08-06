<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseModule extends Model
{
    use HasFactory;

    protected $table = 'course_modules';
    protected $fillable = [
        'title',
        'subtitle',
        'order',
        'active',
        'spec_course_id'
    ];

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function specCourse()
    {
        return $this->belongsTo(SpecCourse::class, 'spec_course_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'course_module_id');
    }
}
