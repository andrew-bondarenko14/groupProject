<?php

namespace App\Http\Controllers\profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Service\ProfileUpdateService;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    private $profileUpdateService;

    public function __construct(ProfileUpdateService $profileUpdateService)
    {
        $this->profileUpdateService = $profileUpdateService;
    }

    public function update(Request $request, UpdateRequest $rules)
    {
        return response()->json($this->profileUpdateService->update($request));
    }
}
