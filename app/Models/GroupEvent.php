<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupEvent extends Model
{
    use HasFactory;

    protected $table = 'group_events';
    protected $fillable = [
        'title',
        'description',
        'active',
        'spec_course_id',
    ];

    public function specCourse()
    {
        return $this->belongsTo(SpecCourse::class, 'spec_course_id');
    }

    public function participantGroups()
    {
        return $this->hasMany(ParticipantGroup::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'group_event_id', 'id');
    }

    public function loadSpecCourse()
    {
        return $this->load([
            'specCourse' => function ($q) {
                $q->with([
                    'modules' => function ($q) {

                        // contar los eventos de cada grupos de eventos?

                        $q->withCount('events');
                    }
                ]);
            },
        ])
            ->loadCount('events', 'participantGroups');
    }



    // public function loadSpecCourse($groupEvent)
    // {
    //     return $this->load([
    //         'specCourse' => function ($q) use ($groupEvent) {
    //             $q->with([
    //                 'modules' => function ($q) use ($groupEvent) {
    //                     $q->whereHas('events', function ($q) use ($groupEvent) {

    //                         $q->where('group_event_id', $groupEvent);

    //                     })->withCount('events');
    //                 }
    //             ]);
    //         },
    //     ])
    //         ->loadCount('events', 'participantGroups');
    // }




}
