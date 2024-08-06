<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebinarEvent extends Model
{
    use HasFactory;

    protected $table = 'webinar_events';

    protected $fillable = [
        'description',
        'date',
        'time_start',
        'time_end',
        'webinar_id',
        'user_id',
        'responsable_id',
        'room_id',
        'active'
    ];

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function webinar()
    {
        return $this->belongsTo(Webinar::class, 'webinar_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function certifications()
    {
        return $this->hasMany(WebinarCertification::class, 'webinar_event_id');
    }

    public function loadRelationships()
    {
        return $this->load(
            [
                'instructor',
                'room',
                'webinar',
                'file' => fn ($q) =>
                $q->where('file_type', 'imagenes')
            ]
        )
        ->loadCount('certifications');
    }

    public function loadRelationShipsForWebinar()
    {
        return $this->load([
            'instructor',
            'room',
            'certifications'
        ]);
    }

    public function loadCertificationsRelationships()
    {
        return $this->load([
            'certifications',
            'room'
        ]);
    }

    public function loadParticipantsCount()
    {
        return $this->loadCount([
            'certifications'
        ]);
    }


}
