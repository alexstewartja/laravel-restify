<?php

namespace Binaryk\LaravelRestify\Http\Controllers;

use Binaryk\LaravelRestify\Http\Requests\ProfileAvatarRequest;

class ProfileAvatarController extends RepositoryController
{
    public function __invoke(ProfileAvatarRequest $request)
    {
        $user = $request->user();

        $request->validate([
            'avatar' => 'required|image',
        ]);

        ProfileAvatarRequest::$path = "avatars/{$user->getKey()}";

        $path = is_callable(ProfileAvatarRequest::$pathCallback) ? call_user_func(ProfileAvatarRequest::$pathCallback, $request) : $request::$path;

        $path = $request->file('avatar')->store($path);

        $user->{$request::$userAvatarAttribute} = $path;
        $user->save();

        return $this->response()->data($user->fresh());
    }
}
