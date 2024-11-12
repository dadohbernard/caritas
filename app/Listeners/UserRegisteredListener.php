<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserRegisteredEvent;
use Illuminate\Auth\Passwords\PasswordBroker;
use App\Mail\userRegistered;
use App\Models\User;
use Mail;
use Hash;
use Str;
class UserRegisteredListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegisteredEvent $event): void
    {
        $info = $event->info;
        $info['manage_user_link'] = route('manage-user');
        $mail = $info['email'];
        $mails = array($mail);
        //password reset mail
        $subject = "User Created Successfully";
        $token = app(PasswordBroker::class)->createToken(User::where('email', $mail)->first());
        $info['tokenUrl'] = url('/reset-password/'.$token.'?email='.$mail);
        Mail::to($mails)->send(new userRegistered($subject, $info));
        //
        //
    }
}
