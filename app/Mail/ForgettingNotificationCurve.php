<?php

namespace App\Mail;

use App\Models\Certification;
use App\Models\FcInstance;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgettingNotificationCurve extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $certification;
    public $fcInstance;

    public function __construct(Certification $certification, FcInstance $fcInstance)
    {
        $this->certification = $certification;
        $this->fcInstance = $fcInstance;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('(no-reply) Ud. tiene una nuevas evaluaciones por realizar.')->markdown('mail.forgetting-notification-curve');
    }
}
