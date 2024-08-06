<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FcInstance extends Model
{
    use HasFactory;

    protected $table = 'f_curve_instances';
    protected $fillable = [
        'title',
        'days_count',
        'forgetting_curve_id',
    ];

    public function forgettingCurve()
    {
        return $this->belongsTo(ForgettingCurve::class, 'forgetting_curve_id');
    }

    public function steps()
    {
        return $this->hasMany(FcStep::class, 'f_curve_instance_id');
    }
}
