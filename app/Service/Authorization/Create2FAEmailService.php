<?php

namespace App\Service\Authorization;

use App\Mail\TwoFAEmail;
use App\Models\PasswordReset;
use App\Models\TwoFactorAuthTokens;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class Create2FAEmailService
{
    public function handleTwoFAEmail($email)
    {
        $user = User::where('email',$email)->first();
        $token = mt_rand(100000, 999999);
        TwoFactorAuthTokens::updateOrCreate(
            ['user_id' => $user->id],
            ['token' => $token]
        );
        $mailData = new \stdClass();
        $mailData->sender = 'IT2.0';
        $mailData->receiver = $user->surname . ' ' . $user->name . ' ' . $user->middle_name;
        $mailData->code = $token;
        return json_encode( Mail::to($user->email)->queue(new TwoFAEmail($mailData)));
    }

}
