<?php


namespace App\Commands\PasswordRecoveryCommands;


use App\Repositories\DB\PasswordReset\DBPasswordResetRepository;
use App\Repositories\UserRepository;
use App\Service\PasswordRecovery\ChangePasswordService;
use App\Service\PasswordRecovery\CreateRecoveryEmailService;

class CheckEmailCommand
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute($request)
    {
        $user = $this->userRepository->getUserByEmail($request['email']);
        if($user) {
            return json_encode(true);
        } else {
            return json_encode(false);
        }
    }
}
