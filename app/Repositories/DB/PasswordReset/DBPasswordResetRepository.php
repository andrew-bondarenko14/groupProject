<?php


namespace App\Repositories\DB\PasswordReset;


use App\Models\PasswordReset;
use App\Models\User;
use App\Models\UserSettings;
use App\Repositories\DB\BaseRepository;
use App\Repositories\PasswordResetRepository;

class DBPasswordResetRepository extends BaseRepository implements PasswordResetRepository
{
    private $model;

    public function getModel()
    {
        return new PasswordReset();
    }

    public function getPasswordResetByToken($token)
    {
        return PasswordReset::where('url_token',$token)->firstOrFail();
    }

    public function getPasswordResetByEmail($email)
    {
        return PasswordReset::where('email', $email)->first();

    }
}
