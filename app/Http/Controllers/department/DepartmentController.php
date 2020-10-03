<?php

namespace App\Http\Controllers\department;

use App\Http\Controllers\Controller;
use App\Http\Requests\departments\StoreRequest;
use App\Service\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    private $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }


    public function index()
    {
        return response()->json($this->departmentService->index());
    }

    public function create()
    {
        //
    }

    public function store(Request $request, StoreRequest $rules)
    {
        return response()->json($this->departmentService->store($request));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        return response()->json($this->departmentService->edit($id));
    }

    public function update(Request $request, StoreRequest $rules)
    {
        return response()->json($this->departmentService->update($request));
    }

    public function destroy(Request $request)
    {
        return response()->json($this->departmentService->destroy($request));
    }

    public function search(Request $request)
    {
        return response()->json($this->departmentService->search($request));
    }
}
