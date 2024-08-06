<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantGroup extends Model
{
    use HasFactory;

    protected $table = 'participant_groups';
    protected $fillable = [
        'title',
        'description',
        'active',
    ];

    public function groupEvent()
    {
        return $this->belongsTo(GroupEvent::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'group_participant', 'group_id', 'participant_id')->withPivot('id');
    }

    public function assignments()
    {
        return $this->morphToMany(Assignment::class, 'assignable')
        ->withPivot(['id','notes','points','status']);
    }

}
