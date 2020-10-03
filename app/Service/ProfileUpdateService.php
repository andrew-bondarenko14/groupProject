<?php


namespace App\Service;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileUpdateService
{
    public function update(Request $request) {
        $user = User::where('id', $request['id'])->first();
        $user->department_id = $request['department_id'];
        $user->position = $request['position'];
        $user->surname = $request['surname'];
        $user->name = $request['name'];
        $user->middle_name = $request['middle_name'];
        $user->birthday = $request['birthday'];
        $user->hired_at = $request['hired_at'];
        $user->email = $request['email'];
        $user->mobile_phone = $request['mobile_phone'];
        $user->work_phone = $request['work_phone'];
        $user->skype_login = $request['skype_login'];

        return $user->save();
    }
}
