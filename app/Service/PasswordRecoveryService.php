<?php


namespace App\Service;

use App\Commands\PasswordRecoveryCommands\ChangePasswordCommand;
use App\Commands\PasswordRecoveryCommands\CheckEmailCommand;
use App\Commands\PasswordRecoveryCommands\ConfirmResetTokenCommand;
use App\Commands\PasswordRecoveryCommands\SendEmailCommand;
use App\Commands\PasswordRecoveryCommands\VerifyTokenCommand;
use App\Models\User;
use App\Repositories\DB\PasswordReset\DBPasswordResetRepository;
use App\Repositories\DB\User\DBUserRepository;
use App\Service\PasswordRecovery\ChangePasswordService;
use App\Service\PasswordRecovery\CreateRecoveryEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PasswordRecoveryService
{

    /**
     * @var CheckEmailCommand
     */
    private $checkEmailCommand;
    /**
     * @var SendEmailCommand
     */
    private $sendEmailCommand;
    /**
     * @var ConfirmResetTokenCommand
     */
    private $resetTokenCommand;
    /**
     * @var ChangePasswordCommand
     */
    private $changePasswordCommand;
    /**
     * @var VerifyTokenCommand
     */
    private $verifyTokenCommand;

    public function __construct(CheckEmailCommand $checkEmailCommand, SendEmailCommand $sendEmailCommand,
                                ConfirmResetTokenCommand $resetTokenCommand,
                                ChangePasswordCommand $changePasswordCommand, VerifyTokenCommand $verifyTokenCommand)
    {

        $this->checkEmailCommand = $checkEmailCommand;
        $this->sendEmailCommand = $sendEmailCommand;
        $this->resetTokenCommand = $resetTokenCommand;
        $this->changePasswordCommand = $changePasswordCommand;
        $this->verifyTokenCommand = $verifyTokenCommand;
    }

    /**
     * Проверка существует ли введеный при восстановлении пароля email в БД
     *
     * @param Request $request
     * @param CheckEmailCommand $checkEmailCommand
     * @return false|string|void
     */
    public function checkEmail(Request $request)
    {
       return $this->checkEmailCommand->execute($request);
    }

    /**
     * Отправление письма на основной или резервный email
     *
     * @param Request $request
     * @param SendEmailCommand $sendEmailCommand
     * @return array|false|string|void
     */
    public function sendEmail(Request $request)
    {
        return $this->sendEmailCommand->execute($request['email'],$request['backupEmail']);
    }

    /**
     * Проверка токена на странице с изменением пароля
     *
     * @param Request $request
     * @param ConfirmResetTokenCommand $resetTokenCommand
     * @return false|string|void
     */
    public function confirmResetToken(Request $request)
    {
         return $this->resetTokenCommand->execute($request);
    }

    /**
     * Изменение пароля пользователя
     *
     * @param Request $request
     * @param ChangePasswordCommand $changePasswordCommand
     * @return false|string|void
     */
    public function changePassword(Request $request)
    {
        return $this->changePasswordCommand->execute($request);
    }

    /**
     * Проверка введенного кода при запросе сброса пароля
     *
     * @param Request $request
     * @param VerifyTokenCommand $verifyTokenCommand
     * @return string|void|false
     */
    public function verify(Request $request)
    {
        return $this->verifyTokenCommand->execute($request);
    }
}
