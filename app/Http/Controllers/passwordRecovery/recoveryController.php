<?php

namespace App\Http\Controllers\passwordRecovery;

use App\Http\Controllers\Controller;
use App\Service\PasswordRecoveryService;
use Illuminate\Http\Request;

class recoveryController extends Controller
{

    /**
     * @var PasswordRecoveryService
     */
    private $service;

    public function __construct(PasswordRecoveryService $service)
    {
        return $this->service = $service;
    }

    /**
     * Проверка существует ли введеный при восстановлении пароля email в БД
     *
     * @param Request $request
     * @return false|string|void
     */
    public function checkEmail(Request $request)
    {
        return $this->service->checkEmail($request);
    }

    /**
     * Отправление письма на основной или резервный email
     *
     * @param Request $request
     * @return array|false|string|void
     */
    public function sendEmail(Request $request)
    {
        return $this->service->sendEmail($request);
    }

    /**
     * Проверка токена на странице с изменением пароля
     *
     * @param Request $request
     * @return false|string|void
     */
    public function confirmResetToken(Request $request)
    {
        return $this->service->confirmResetToken($request);
    }

    /**
     * Изменение пароля пользователя
     *
     * @param Request $request
     * @return false|string|void
     */
    public function changePassword(Request $request)
    {
        return $this->service->changePassword($request);
    }

    /**
     * Проверка введенного кода при запросе сброса пароля
     *
     * @param Request $request
     * @return string|void|false
     */
    public function verify(Request $request)
    {
       return $this->service->verify($request);
    }
}

