<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $fillable = [
        'whatsapp_number',
        'whatsapp_message',
        'email',
        'facebook_link',
        'linkedin_link',
        'instagram_link',
        'logo_url'
    ];

    protected $table = 'configs';

}
