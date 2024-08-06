<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreecourseDetail extends Model
{
    use HasFactory;

    protected $table = 'freecourse_details';
    protected $fillable = [
        'course_id',
        'description',
        'price',
    ];

    public function freecourse()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

}
