<?php


namespace App\Commands\UserSettingsCommands;


class ChangeBackupEmailCommand
{
    public function execute($request)
    {
        $user = $request->user();
        $user->settings->backup_email = $request['backup_email'];

        return json_encode($user->settings->save());
    }
}
