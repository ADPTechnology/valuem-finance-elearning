<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FcVideoQuestion extends Model
{
    use HasFactory;

    protected $table = 'f_curve_video_questions';
    protected $fillable = [
        'statement',
        'correct_answer',
        'points',
        'f_curve_video_id'
    ];

    // ! DELETE THIS MODEL

    public function video()
    {
        return $this->belongsTo(FcVideo::class, 'f_curve_video_id');
    }
}
