<?php


namespace App\Repositories\DB\UserSecurityLogs;


use App\Models\UserSecurityLogs;
use App\Repositories\DB\BaseRepository;
use App\Repositories\UserSecurityLogsRepository;

class DBUserSecurityLogsRepository extends BaseRepository implements UserSecurityLogsRepository
{
    public function getModel()
    {
        return new UserSecurityLogs();
    }

    public function getLogsByUserId($userId)
    {
        return UserSecurityLogs::where('user_id',$userId);
    }

    public function deleteUserLogs($logsToDelete)
    {
        return $logsToDelete->delete();
    }

    public function getActionsLogsByUserId($userId)
    {
        $allLogs = $this->getLogsByUserId($userId);

        return $allLogs->where(function ($query) { $query->where('action','!=','Вход в систему')->where('action','!=','Выход из системы');});
    }

    public function getLoginLogsByUserId($userId)
    {
        $allLogs = $this->getLogsByUserId($userId);

        return $allLogs->where('action','like','Вход в систему')->orWhere('action','like','Выход из системы');
    }
}
