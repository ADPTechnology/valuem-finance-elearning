<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCertification extends Model
{
    use HasFactory;

    protected $table = 'product_certifications';

    protected $fillable = [
        'user_id',
        'flg_finished',
        'status',
        'score',
        'certificable_id',
        'certificable_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function freeCourseEvaluations()
    {
        return $this->belongsToMany(FreecourseEvaluation::class, 'user_fc_evaluations', 'p_certification_id', 'fc_evaluation_id')
            ->withPivot(['notes', 'points', 'status'])->withTimestamps();
    }

    public function evaluations()
    {
        return $this->hasMany(UserFcEvaluation::class, 'p_certification_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'certificable_id');
    }

    public function getDateEsAttribute()
    {
        $date_carbon = Carbon::parse($this->updated_at);
        $month_es = config('parameters.months_es')[$date_carbon->isoFormat('MM')];

        return $date_carbon->isoFormat('DD') . ' de ' . $month_es . ' del ' . $date_carbon->isoFormat('YYYY');
    }

}
