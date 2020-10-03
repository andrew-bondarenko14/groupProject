<?php


namespace App\Commands\UserSettingsCommands;


use Carbon\Carbon;

class ChangeUserPasswordCommand
{
    public function execute($request)
    {
        $user = $request->user();
        $user->password = bcrypt($request['password']);
        $user->settings->password_changed_at = Carbon::now();
        $user->settings->save();

        return json_encode($user->save());
    }
}
