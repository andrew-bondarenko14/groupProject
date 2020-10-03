<?php


namespace App\Service;
use App\Mail\RecoveryEmail;
use App\Mail\UserPasswordEmail;
use App\Models\User;
use App\Models\UserAccesses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class UserService
{
    public function index()
    {
        return User::get();
    }

    public function edit($id)
    {
        $user = User::where('id',$id)->with('accesses')->first();
        if($user->accesses == null);
        $user->accesses()->save(new \App\Models\UserAccesses());
        $user = $user->fresh();
        return User::where('id',$id)->with('accesses')->get();
    }

    public function update(Request $request, $id)
    {
        /*$request['avatar'] = '';*/
        if (!$request['avatar_url']) {
            $request['avatar_url'] = '';
        };
        if (!$request['hired_at']) {
            $request['hired_at'] = null;
        }
        $data = $request->validate([
            'avatar_url' => 'string|present',
            'surname' => 'required|max:255|string',
            'name' => 'required|max:255|string',
            'middle_name' => 'max:255|string|nullable',
            'birthday' => 'required|date',
            'department_id' => 'integer|nullable',
            'position' => 'required|max:255|string',
            'hired_at' => 'date|nullable',
            'is_admin' => 'boolean',
            'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i|unique:users,email,'.$request['id'].'|max:255',
            'mobile_phone' => 'string|max:12|required|unique:users,mobile_phone,'.$request['id'].'',
            'work_phone' => 'string|max:12||required|unique:users,work_phone,'.$request['id'].'',
            'skype_login' => 'string|max:255|required|unique:users,skype_login,'.$request['id'].'',
            'accesses' => 'array|nullable',
            'accesses.disk' => 'integer',
            'accesses.mail' => 'integer',
            'accesses.calendar' => 'integer',
            'accesses.photos' => 'integer',
            'accesses.contacts' => 'integer',
        ]);
        if ($data['accesses']) {
            User::where('id',$id)->first()->accesses->update($data['accesses']);
            unset($data['accesses']);
        }
        User::where('id', $id)->update($data);
    }

    public function store(Request $request)
    {
        $password = null;
        $request['avatar_url'] = '';
        if (!$request['hired_ad']) {
            $request['hired_ad'] = null;
        }
        if ($request['is_admin'] === null) {
            $request['is_admin'] = 0;
        };
        if (!$request['middle_name']) {
            $request['middle_name'] = '';
        };
        $data = $request->validate([
            'avatar_url' => 'string|present',
            'surname' => 'required|max:255|string',
            'name' => 'required|max:255|string',
            'middle_name' => 'max:255|string',
            'birthday' => 'required|date',
            'department_id' => 'integer',
            'position' => 'required|max:255|string',
            'hired_at' => 'nullable|date',
            'is_admin' => 'boolean',
            'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i|unique:users,email|max:255',
            'mobile_phone' => 'required|string|max:12|unique:users,mobile_phone',
            'work_phone' => 'required|string|max:12|unique:users,work_phone',
            'skype_login' => 'required|string|max:255|unique:users,skype_login'
        ]);
        $password = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ,./|@#$%^&*()'), 0, 10);;
        $data['password'] = Hash::make($password);
        $createdPasswordData = new \stdClass();
        $createdPasswordData->receiver = $data['surname'] . ' ' . $data['name'] . ' ' . $data['middle_name'];
        $createdPasswordData->email = $data['email'];
        $createdPasswordData->password = $password;
        User::create($data);
        Mail::to($data['email'])->send(new UserPasswordEmail($createdPasswordData));
    }

    public function destroy(Request $request)
    {
        foreach ($request->all()['users'] as $value) {
            if (Auth::id() === $value) {
                $result = [
                    'result' => false,
                    'message' => 'Вы не можете удалить свой аккаунт'
                ];
                return $result;
            } else {
                $user = User::findOrFail($value);
                $user->delete();
            }
        }
    }
}
