<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Services\UserService;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RoleController
{
    private $userService;

    public function __constructor(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $this->userService->allows('view', 'roles');

        return RoleResource::collection(Role::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->userService->allows('edit', 'roles');

        $role = Role::create($request->only('name'));

        if($permissions = $request->input('permissions')) {
            foreach($permissions as $permission_id) {
                DB::table('role_permission')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id
                ]);
            }
        }

        return response(new RoleResource($role), Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $this->userService->allows('view', 'roles');

        return new RoleResource(Role::find($id));
    }

    public function update(Request $request, $id)
    {
        $this->userService->allows('edit', 'roles');

        $role = Role::find($id);

        $role->update($request->only('name'));

        // remove the old relationship first
        DB::table('role_permission')->where('role_id', $role->id)->delete();

        // insert new relationship
        if($permissions = $request->input('permissions')) {
            foreach($permissions as $permission_id) {
                DB::table('role_permission')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id
                ]);
            }
        }

        return response(new RoleResource($role), Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        $this->userService->allows('edit', 'roles');

        // remove permission first
        DB::table('role_permission')->where('role_id', $id)->delete();

        Role::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
