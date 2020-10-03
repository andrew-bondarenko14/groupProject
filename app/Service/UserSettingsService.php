<?php


namespace App\Service;


use App\Commands\UserSettingsCommands\ChangeBackupEmailCommand;
use App\Commands\UserSettingsCommands\ChangeSettingsCommand;
use App\Commands\UserSettingsCommands\ChangeUserPasswordCommand;
use App\Commands\UserSettingsCommands\DeleteLogsCommand;
use App\Http\Requests\userSettings\ChangeBackupEmail;
use App\Http\Requests\userSettings\ChangePassword;
use App\Http\Requests\userSettings\ChangeSettings;
use Illuminate\Http\Request;

class UserSettingsService
{

    /**
     * @var ChangeUserPasswordCommand
     */
    private $passwordCommand;
    /**
     * @var ChangeBackupEmailCommand
     */
    private $backupEmailCommand;
    /**
     * @var ChangeSettingsCommand
     */
    private $settingsCommand;
    /**
     * @var DeleteLogsCommand
     */
    private $deleteLogsCommand;

    public function __construct(ChangeUserPasswordCommand $passwordCommand, ChangeBackupEmailCommand $backupEmailCommand,
                                ChangeSettingsCommand $settingsCommand, DeleteLogsCommand $deleteLogsCommand)
    {
        $this->passwordCommand = $passwordCommand;
        $this->backupEmailCommand = $backupEmailCommand;
        $this->settingsCommand = $settingsCommand;
        $this->deleteLogsCommand = $deleteLogsCommand;
    }

    /**
     * Изменение пароля пользователя
     *
     * @param ChangePassword $request
     * @param ChangeUserPasswordCommand $passwordCommand
     * @return false|string|void
     */
    public function changePassword(ChangePassword $request)
    {
        return $this->passwordCommand->execute($request);
    }

    /**
     * Изменение резервного email пользователя
     *
     * @param ChangeBackupEmail $request
     * @param ChangeBackupEmailCommand $backupEmailCommand
     * @return false|string|void
     */
    public function changeBackupEmail(ChangeBackupEmail $request)
    {
        return $this->backupEmailCommand->execute($request);
    }

    /**
     * Изменение настроек пользователя
     *
     * @param ChangeSettings $request
     * @param ChangeSettingsCommand $settingsCommand
     * @return false|string|void
     */
    public function changeSettings(ChangeSettings $request)
    {
        return $this->settingsCommand->execute($request);
    }

    /**
     * Удаление выбранных пользователем логов
     *
     * @param Request $request
     * @param DeleteLogsCommand $deleteLogsCommand
     * @return bool
     */
    public function clearLogs(Request $request)
    {
        return $this->deleteLogsCommand->execute($request);
    }
}
