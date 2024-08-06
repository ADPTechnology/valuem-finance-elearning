<?php

namespace App\Mail;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FreeCourseRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $course;
    public $admin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Course $course, User $admin)
    {
        $this->user = $user;
        $this->course = $course;
        $this->admin = $admin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('(no-reply) Solicitud de acceso a un curso libre')->markdown('mail.free-course-request');
    }
}
