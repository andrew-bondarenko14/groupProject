<?php


namespace App\Commands\AuthCommands;


use App\Repositories\DB\User\DBUserRepository;
use App\Service\reCaptchaService;
use Illuminate\Support\Facades\Auth;

class LogoutUserCommand
{
    public function execute()
    {
        if(Auth::user()->token())
            Auth::user()->token()->revoke();
        Auth::logout();

        return redirect('/authorization') ;
    }
}
