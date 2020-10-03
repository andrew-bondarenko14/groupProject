<?php


namespace App\Service;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvatarUpdateService
{
    public function update(Request $request) {
        $user = User::where('id', $request['id'])->first();
        if ($request->file) {
            $newPath = Storage::disk('public_uploads')->put('', $request->file);
            $user->avatar_url = $newPath;;
        }

        return $user->save();
    }
}
