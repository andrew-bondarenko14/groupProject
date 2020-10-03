<?php


namespace App\Commands\PasswordRecoveryCommands;


use App\Mail\RecoveryEmail;
use App\Models\PasswordReset;
use App\Models\User;
use App\Repositories\DB\User\DBUserRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class ChangePasswordCommand
{
    public function execute($request)
    {
        if(PasswordReset::where('url_token',$request['token'])->first()) {
            $user = User::where('email', $request['email'])->first();
            $user->password = bcrypt($request['password']);
            return json_encode($user->save());
        } else if($request['token'] == 'codeword') {
            $user = User::where('email', $request['email'])->first();
            if ($user->settings->codeword == $request['codeword']) {
                $user->password = bcrypt($request['password']);
                return json_encode($user->save());
            } else

                return json_encode('Неверное кодовое слово');
        }

        return null;
    }
}
