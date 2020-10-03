<?php

namespace App\Http\Controllers\profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AvatarUpdateRequest;
use App\Service\AvatarUpdateService;
use Illuminate\Http\Request;


class AvatarController extends Controller
{
    private $avatarUpdateService;

    public function __construct(AvatarUpdateService $avatarUpdateService)
    {
        $this->avatarUpdateService = $avatarUpdateService;
    }

    public function update(Request $request, AvatarUpdateRequest $rules)
    {
        return response()->json($this->avatarUpdateService->update($request));
    }
}
