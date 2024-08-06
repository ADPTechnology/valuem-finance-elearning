<?php

namespace App\Models;

use App\Services\FcEvaluationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFcEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'fc_evaluation_id',
        'p_certification_id',
        'notes',
        'points',
        'status'
    ];

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'user_fc_evaluation_id');
    }

    public function fcEvaluation()
    {
        return $this->belongsTo(FreecourseEvaluation::class, 'fc_evaluation_id');
    }

    public function productCertification()
    {
        return $this->belongsTo(ProductCertification::class, 'p_certification_id');
    }

}
