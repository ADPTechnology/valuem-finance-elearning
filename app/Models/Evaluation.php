<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certification;

class Evaluation extends Model
{
    use HasFactory;

    protected $table = 'evaluations';
    protected $fillable = [
        'evaluation_time',
        'statement',
        'correct_alternatives',
        'selected_alternatives',
        'points',
        'is_correct',
        'question_order',
        'question_id',
        'certification_id',
        'f_curve_step_progress_id',
        'user_fc_evaluation_id'
    ];


    public function userFcEvaluation()
    {
        return $this->belongsTo(UserFcEvaluation::class, 'user_fc_evaluation_id');
    }

    public function question()
    {
        return $this->belongsTo(DynamicQuestion::class, 'question_id', 'id');
    }
}
