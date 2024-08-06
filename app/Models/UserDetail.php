<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'courses_count',
        'facebook_link',
        'linkedin_link',
        'instagram_link',
        'pag_web_link',
        'email',
    ];

    protected $table = 'user_details';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
