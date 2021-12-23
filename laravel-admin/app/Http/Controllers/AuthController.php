<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController
{
    private $userService;

    public function __constructor(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function user(Request $request)
    {
        $user = $this->userService->getUser();

        $resource = new UserResource($user);

        if ($user->isInfluencer() && !$user->isAdmin()) {
            return $resource->additional([
                'data' => [
                    'revenue' => $user->revenue()
                ]
            ]);
        }

        $permissionInfo = $user->role() && $user->permissions() ? $user->permissions() : [];

        return $resource->additional([
            'data' => [
                'role' => $user->role(),
                'permissions' => $permissionInfo
            ]
        ]);
    }
}
