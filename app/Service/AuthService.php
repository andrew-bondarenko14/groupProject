<?php


namespace App\Service;

use App\Commands\AuthCommands\LoginUserCommand;
use App\Commands\AuthCommands\LogoutUserCommand;
use App\Http\Requests\Login;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class AuthService
{

    /**
     * @var LoginUserCommand
     */
    private $loginUserCommand;
    /**
     * @var LogoutUserCommand
     */
    private $logoutUserCommand;

    public function __construct(LoginUserCommand $loginUserCommand,LogoutUserCommand $logoutUserCommand)
    {
        $this->loginUserCommand = $loginUserCommand;
        $this->logoutUserCommand = $logoutUserCommand;
    }

    /**
     * Авторизировать пользователя
     *
     * @param Login $request
     * @return array|JsonResponse
     */

    public function login(Login $request)
    {
        return $this->loginUserCommand->execute($request);
        // return $this->loginService->handleUserLogin($request);
    }

    /**
     * Выйти из аккаунта и уничтожить все токены юзера
     *
     * @return RedirectResponse|Redirector|void
     */

    public function logout()
    {
        return $this->logoutUserCommand->execute();
        //return $this->logoutService->handleUserLogout();
    }
}
