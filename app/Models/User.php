<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Notifications\Notifiable;
use App\Models\{
    Certification,
    Event,
    Company,
    MiningUnit,
    Publishing,
    SectionChapter,
    UserSurvey
};

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dni',
        'name',
        'paternal',
        'maternal',
        'email',
        'password',
        'telephone',
        'role',
        'cip',
        'signature',
        'active',
        'company_id',
        'position',
        'profile_survey',
        'profile_user',
        'user_type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function publishings()
    {
        return $this->hasMany(Publishing::class, 'user_id', 'id');
    }

    public function progressChapters()
    {
        return $this->belongsToMany(SectionChapter::class, 'user_course_progress', 'user_id', 'section_chapter_id')
            ->withPivot(['id', 'progress_time', 'last_seen', 'status'])->withTimestamps();
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }


    // ------------ SHOPPING CART -------------


    public function cart()
    {
        return $this->hasMany(ShoppingCart::class, 'user_id');
    }

    public function cartCourses()
    {
        return $this->morphedByMany(Course::class, 'buyable', 'shopping_cart')
                    ->withPivot(['quantity'])->withTimestamps();
    }


    // ------------ ORDERS ------------------

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    // ------------- PRODUCT CERTIFICATIONS --------

    public function productCertifications()
    {
        return $this->hasMany(ProductCertification::class, 'user_id');
    }

    public function productCoursesCertifications()
    {
        return $this->morphedByMany(Course::class, 'certificable', 'product_certifications')
                    ->withPivot(['flg_finished', 'status', 'score'])->withTimestamps();
    }








    static function getInstructorsQuery()
    {
        return User::whereIn('role', ['instructor']);
    }

    static function getResponsablesQuery()
    {
        return User::where('company_id', 10)
            ->orWhere(function ($q) {
                $q->whereHas('company', function ($q2) {
                    $q2->whereRaw("(LOWER(companies.description) LIKE '%hama%')");
                });
            });
    }

    public function avatar()
    {
        return $this->file()->where('category', 'avatars')->first();
    }

    public function signature()
    {
        return $this->file()->where('category', 'firmas')->first();
    }


    public function userDetail(): HasOne
    {
        return $this->hasOne(UserDetail::class, 'user_id', 'id');
    }


    public function loadAvatar()
    {
        return $this->load([
            'file' => fn($q) =>
                $q->where('category', 'avatars')
        ]);
    }

    public function sliderImages()
    {
        return $this->hasMany(SliderImage::class, 'user_id', 'id');
    }

    public function loadFilesParticipant()
    {
        return $this->load([
            'files' => fn($q) =>
                $q->where('file_type', 'archivos')
                    ->whereIn('category', ['participantDoc'])
        ]);
    }


    /* ----------- ACCESSORS ------------*/


    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->paternal;
    }

    public function getFullNameCompleteAttribute()
    {
        return $this->name . ' ' . $this->paternal . ' ' . $this->maternal;
    }

    public function getFullNameCompleteReverseAttribute()
    {
        return $this->paternal . ' ' . $this->maternal . ', ' . $this->name;
    }
    public function getFullSurnameAttribute()
    {
        return $this->paternal . ' ' . $this->maternal;
    }



}
