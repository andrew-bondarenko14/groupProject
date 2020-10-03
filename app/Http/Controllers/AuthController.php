<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login;
use App\Service\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class AuthController extends Controller
{

    private $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * Авторизировать пользователя
     *
     * @param Login $request
     * @return array|JsonResponse
     */

    public function login(Login $request)
    {
        return $this->service->login($request);
    }

    /**
     * Выйти из аккаунта и уничтожить все токены юзера
     *
     * @return RedirectResponse|Redirector|void
     */

    public function logout()
    {
        return $this->service->logout();
    }

}

