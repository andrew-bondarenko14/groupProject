<?php

namespace App\Repositories;

interface UserSecurityLogsRepository
{
    public function getModel();

    public function getLogsByUserId($userId);

    public function deleteUserLogs($logsToDelete);

    public function getActionsLogsByUserId($userId);

    public function getLoginLogsByUserId($userId);
}
