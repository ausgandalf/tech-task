<?php

namespace App\Http\Controllers\Api;

use App\Application\User\Actions\CreateUserAction;
use App\Application\User\Actions\UpdateUserAction;
use App\Application\User\Actions\GetUserAction;
use App\Application\User\Actions\ListUsersAction;
use App\Application\User\Actions\DeleteUserAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private CreateUserAction $createUserAction,
        private UpdateUserAction $updateUserAction,
        private GetUserAction $getUserAction,
        private ListUsersAction $listUsersAction,
        private DeleteUserAction $deleteUserAction
    ) {}

    public function store(Request $request)
    {
        $user = $this->createUserAction->execute($request);
        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = $this->updateUserAction->execute($id, $request, true);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function show($id)
    {
        $user = $this->getUserAction->execute($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function index()
    {
        $users = $this->listUsersAction->execute();
        return response()->json($users);
    }

    public function destroy($id)
    {
        $this->deleteUserAction->execute($id);
        return response()->json(['message' => 'User deleted'], 204);
    }
}
