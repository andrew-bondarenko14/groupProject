<?php


namespace App\Service;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class DepartmentService
{
    public function index()
    {
        return Department::with('director', 'users')->get();
    }

    public function edit($id)
    {
        $department = Department::with('director', 'users')->where('id', $id)->get();
        return $department;
    }

    public function update(Request $request)
    {
        $departmentsToUpdate = Department::where('director_user_id', $request->director_user_id)->get();
        foreach ($departmentsToUpdate as $departmentToUpdate) {
            $departmentToUpdate->director_user_id = 0;
            $departmentToUpdate->save();
        }
        $department = Department::where('id', $request->id)->first();
        $department->name = $request->name;
        $department->director_user_id = $request->director_user_id;
        $department->save();
        $user = User::where('id', $request->director_user_id)->first();
        $user->department_id = $department->id;

        return $user->save();
    }

    public function store(Request $request)
    {
        $departmentsToUpdate = Department::where('director_user_id', $request->director_user_id)->get();
        foreach ($departmentsToUpdate as $departmentToUpdate) {
            $departmentToUpdate->director_user_id = null;
            $departmentToUpdate->save();
        }
        $department = new Department();
        $department->name = $request->name;
        $department->director_user_id = $request->director_user_id;
        $department->save();
        $user = User::where('id', $request->director_user_id)->first();
        $user->department_id = $department->id;

        return $user->save();
    }

    public function destroy(Request $request)
    {
        foreach ($request->data as $id) {
            $data = Department::where('id', $id)->first();
            $data->delete();
        }

        return true;
    }
}
