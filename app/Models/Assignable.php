<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignable extends Model
{
    use HasFactory;

    protected $table = 'assignables';
    
    protected $fillable = [
        'notes',
        'points',
        'status',
        'assignment_id',
        'assignable_id',
        'assignable_type'
    ];

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

}
