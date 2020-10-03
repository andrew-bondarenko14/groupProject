<?php


namespace App\Commands\PasswordRecoveryCommands;


use App\Repositories\DB\PasswordReset\DBPasswordResetRepository;

class VerifyTokenCommand
{

    private $resetRepository;

    public function __construct(DBPasswordResetRepository $resetRepository)
    {
        $this->resetRepository = $resetRepository;
    }

    public function execute($request)
    {
        return $this->resetRepository
            ->getPasswordResetByToken($request['token'])
            ->email;
    }
}
