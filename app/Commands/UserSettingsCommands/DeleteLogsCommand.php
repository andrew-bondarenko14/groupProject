<?php


namespace App\Commands\UserSettingsCommands;


use App\Repositories\DB\UserSecurityLogs\DBUserSecurityLogsRepository;

class DeleteLogsCommand
{
    private $userSecurityLogsRepository;

    public function __construct(DBUserSecurityLogsRepository $userSecurityLogsReporitory)
    {
        $this->userSecurityLogsRepository = $userSecurityLogsReporitory;
    }

    public function execute($request)
    {
        if ($request->get('toDelete') == 'logins')
            $toDelete = $this->userSecurityLogsRepository->getLoginLogsByUserId($request->user()->id);
        if ($request->get('toDelete') == 'actions')
            $toDelete = $this->userSecurityLogsRepository->getActionsLogsByUserId($request->user()->id);
        if($toDelete) {
            return $this->userSecurityLogsRepository->deleteUserLogs($toDelete);
        }

        return false;
    }
}
