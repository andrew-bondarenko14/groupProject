<?php

namespace App\Repositories\DB\User;

use App\Models\User;
use App\Models\UserAccesses;
use App\Models\UserSettings;
use App\Repositories\DB\BaseRepository;
use App\Repositories\UserRepository;

class DBUserRepository extends BaseRepository implements UserRepository
{
    private $model;

    public function getModel()
    {
        return new User();
    }

    public function getUserById($id)
    {
        $user = User::where('id',$id)->first();
        $user = $this->checkUserRelations($user);

        return $user;
    }

    public function getUserByEmail($email)
    {
        $user = User::where('email',$email)->first();
        $user = $this->checkUserRelations($user);

        return $user;
    }

    private function checkUserRelations($user)
    {
        if ($user) {
            $user = $this->findOrCreateSettings($user);
            $user = $this->findOrCreateAccesses($user);
        }

        return $user;
    }

    private function findOrCreateSettings($user)
    {
        if($user->settings == null) {
            $user->settings()->save(new UserSettings());
            $user = $user->fresh();
        }

        return $user;
    }

    private function findOrCreateAccesses($user)
    {
        if($user->accesses == null) {
            $user->accesses()->save(new UserAccesses());
            $user = $user->fresh();
        }

        return $user;
    }
}
