<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebinarCertification extends Model
{
    use HasFactory;

    protected $table = 'webinar_certifications';

    protected $fillable = [
        'user_id',
        'webinar_event_id',
        'observation',
        'unlock_cert',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(WebinarEvent::class, 'webinar_event_id');
    }


    public function getUnlockCertCheckedAttribute()
    {
        return $this->unlock_cert == 'S' ? 'checked' : '';
    }
}
