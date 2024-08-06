<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;

    protected $table = 'assignments';
    protected $fillable = [
        'title',
        'description',
        'value',
        'date_limit',
        'flg_groupal',
        'flg_evaluable',
        'active',
        'event_id'
    ];


    public function certifications()
    {
        return $this->morphedByMany(Certification::class, 'assignable')
        ->withPivot(['id', 'notes', 'points', 'status'])
        ->withTimestamps();
    }

    public function participantGroups()
    {
        return $this->morphedByMany(ParticipantGroup::class, 'assignable')
        ->withPivot(['id', 'notes', 'points', 'status'])
        ->withTimestamps();
    }

    public function assignables()
    {
        return $this->hasMany(Assignable::class, 'assignment_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function loadFiles()
    {
        return $this->load('files')->loadCount('files');
    }

}
