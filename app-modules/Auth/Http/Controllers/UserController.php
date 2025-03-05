<?php

namespace AppModules\Auth\Http\Controllers;

use AppModules\Auth\Http\Resources\UserResource;
use AppModules\Auth\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController
{
    public function index(): AnonymousResourceCollection
    {
        $users = User::query()->paginate(10);
        return UserResource::collection($users);
    }

    public function me(): UserResource
    {
        return new UserResource(auth()->user());
    }
}
