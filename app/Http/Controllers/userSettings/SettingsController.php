<?php

namespace App\Http\Controllers\userSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\userSettings\ChangeBackupEmail;
use App\Http\Requests\userSettings\ChangePassword;
use App\Http\Requests\userSettings\ChangeSettings;
use App\Service\UserSettings\ChangeSettingsService;
use App\Service\UserSettings\DeleteLogsService;
use App\Service\UserSettings\ChangeBackupEmailService;
use App\Service\UserSettings\ChangePasswordService;
use App\Service\UserSettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * @var UserSettingsService
     */
    private $service;

    /**
     * SettingsController constructor.
     * @param UserSettingsService $service
     */
    public function __construct(UserSettingsService $service)
    {
        $this->service = $service;
    }

    /**
     * Изменение пароля пользователя
     *
     * @param ChangePassword $request
     * @return false|string|void
     */
    public function changePassword(ChangePassword $request)
    {
        return $this->service->changePassword($request);
    }

    /**
     * Изменение резервного email пользователя
     *
     * @param ChangeBackupEmail $request
     * @return false|string|void
     */
    public function changeBackupEmail(ChangeBackupEmail $request)
    {
        return $this->service->changeBackupEmail($request);
    }

    /**
     * Изменение настроек пользователя
     *
     * @param ChangeSettings $request
     * @return false|string|void
     */
    public function changeSettings(ChangeSettings $request)
    {
        return $this->service->changeSettings($request);
    }

    /**
     * Удаление выбранных пользователем логов
     *
     * @param Request $request
     * @return bool
     */
    public function clearLogs(Request $request)
    {
        return $this->service->clearLogs($request);
    }

}
