<?php


namespace App\Commands\PasswordRecoveryCommands;


use App\Mail\RecoveryEmail;
use App\Models\PasswordReset;
use App\Repositories\DB\User\DBUserRepository;
use App\Repositories\PasswordResetRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class ConfirmResetTokenCommand
{
    private $resetRepository;

    public function __construct(PasswordResetRepository $resetRepository)
    {
        $this->resetRepository = $resetRepository;
    }

    public function execute($request)
   {
       $recovery = $this->resetRepository->getPasswordResetByEmail($request['email']);
       if ($recovery->reset_token == $request['token'])
           return json_encode($recovery->url_token);
       else
           return json_encode(false);
   }
}
