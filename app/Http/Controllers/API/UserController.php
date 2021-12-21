<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json(['users' => UserResource::collection($users), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ])->givePermissionTo('user');

        return response()->json(['message' => 'successfully registered user', 'user' => $user], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $userPermissions = $user->getPermissionNames();
        $arrPermissions = array();

        foreach ($user->getPermissionNames() as $userPermissions) {
            $arrPermissions[] = $userPermissions;
        }

        if (!array_search('admin', $arrPermissions) && (Auth::User()->id != $user->id)) {
            return response(['message' => 'User does not have permission to use this feature']);
        }

        return response()->json(['user' => new UserResource($user), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $userPermissions = $user->getPermissionNames();
        $arrPermissions = array();

        foreach ($user->getPermissionNames() as $userPermissions) {
            $arrPermissions[] = $userPermissions;
        }

        if (!array_search('admin', $arrPermissions) && (Auth::User()->id != $user->id)) {
            return response(['message' => 'User does not have permission to use this feature']);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['user' => new UserResource($user), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $userPermissions = $user->getPermissionNames();
        $arrPermissions = array();

        foreach ($user->getPermissionNames() as $userPermissions) {
            $arrPermissions[] = $userPermissions;
        }

        if (!array_search('admin', $arrPermissions)) {
            return response(['message' => 'User does not have permission to use this feature']);
        }

        $user->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }
}
