<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification as BaseSendEmailVerificationNotification; // Ini penting jika Anda menggunakan Laravel versi lama

class SendEmailVerificationNotification extends BaseSendEmailVerificationNotification
{
    
    public function __construct()
    {
        //
    }

    
    public function handle(Registered $event): void
    {
        if ($event->user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            $event->user->sendEmailVerificationNotification();
        }
    }
}