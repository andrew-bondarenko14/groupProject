<?php


namespace App\Commands\UserSettingsCommands;


use App\Mail\BackupPasswordEmail;
use Illuminate\Support\Facades\Mail;

class ChangeSettingsCommand
{
    public function execute($request)
    {
        $user = $request->user();
        $user->settings->two_step_authentication = +$request['two_step_authentication'];
        $user->settings->security_notifications = +$request['security_notifications'];
        $user->settings->codeword = $request['codeword'] ?? '' ;
        $user->settings->backup_password = $request['backup_password'] ? $this->handleBackupPasswordChange($user) : '';

        return json_encode($user->settings->save());
    }

    private function handleBackupPasswordChange($user)
    {
        if (!$user->settings->backup_password) {
            $backupPassword = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ,./|@#$%^&*()'), 0, 10);
            $mailData = new \stdClass();
            $mailData->sender = 'IT2.0';
            $mailData->receiver = $user->surname . ' ' . $user->name . ' ' . $user->middle_name;
            $mailData->code = $backupPassword;
            Mail::to($user->email)->queue(new BackupPasswordEmail($mailData));

            return $backupPassword;
        }

        return $user->settings->backup_password;
    }
}
