<?php


namespace App\Commands\PasswordRecoveryCommands;


use App\Mail\RecoveryEmail;
use App\Models\PasswordReset;
use App\Repositories\DB\User\DBUserRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SendEmailCommand
{
    private $userRepository;

    public function __construct(DBUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute($email,$backupEmail = null)
    {
        $user = $this->userRepository->getUserByEmail($email);
        if ($backupEmail && $user)
        {
            if ($user->settings->backup_email != $backupEmail)
                return json_encode(false);
        }
        $token = mt_rand(100000, 999999);
        if($user) {
            return $this->createMail($user,$backupEmail,$token);
        } else
            return json_encode(false);
    }

    private function createMail($user,$backupEmail,$token)
    {
        $recovery = PasswordReset::firstOrCreate(
            ['email' => $user->email],
            ['reset_token' => $token, 'url_token' => '']
        );
        $recovery->reset_token = $token;
        $recovery->url_token = bcrypt($token . $user->email . Carbon::now());
        $recovery->created_at = Carbon::now();
        $recovery->save();
        $recoveryData = new \stdClass();
        $recoveryData->sender = 'SenderUserName';
        $recoveryData->receiver = $user->surname . ' ' . $user->name . ' ' . $user->middle_name;
        $recoveryData->code = $token;
        if (!$backupEmail)
            Mail::to($user->email)->send(new RecoveryEmail($recoveryData));
        else
            Mail::to($user->settings->backup_email)->send(new RecoveryEmail($recoveryData));

        return [
            'backupEmail' => $user->settings->backup_email ?? null,
            'codeword' => $user->settings->codeword ? true : '',
        ];
    }
}
