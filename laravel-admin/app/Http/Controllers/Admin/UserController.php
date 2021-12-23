<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Jobs\AdminAdded;
use App\Models\UserRole;
use App\Services\UserService;
use http\Client\Request;
use Illuminate\Http\Response;

class UserController
{
    private $userService;

    public function __constructor(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $this->userService->allows('view', 'users');

        return $this->userService->all($request->input('page', 1));

//        $users = User::paginate();
//        return UserResource::collection($users);

        // return User::with('role')->paginate();
    }

    public function show($id)
    {
        $this->userService->allows('view', 'users');

        // using UserResource
        //$user = User::find($id);
        $user = $this->userService->get($id);
        return new UserResource($user);

        //return User::with('role')->find($id);
    }

    public function store(UserCreateRequest $request)
    {
        $this->userService->allows('edit', 'users');

        $data = $request->only('first_name', 'last_name', 'email') + [
                'password' => $request->input('password')]; // no harsh

        $user = $this->userService->create($data);

        //        $user = User::create(
//            $request->only('first_name', 'last_name', 'email') + [
//            'password' => Hash::make($request->input('password'))
//        ]);

        // add user's role to pivot table
        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id')
        ]);

        // Dispatch queue job to RabbitMq
        AdminAdded::dispatch($user->email);

        // fire event
        //event(new AdminAddedEvent($user));

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $this->userService->allows('edit', 'users');

        $user = $this->userService->update($id, $request->only('first_name', 'last_name', 'email', 'password'));

//        $user = User::find($id);
//
//        $user->update($request->only('first_name', 'last_name', 'email'));

//        if( '' !== $request->only('password') ) {
//            $user->update(['password' => Hash::make($request->input('password'))]);
//        }

        // remove and re-add user's role to pivot table
        UserRole::where('user_id', $user->id)->delete();
        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id')
        ]);

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        $this->userService->allows('edit', 'users');

        $this->userService->delete($id);
        //User::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
