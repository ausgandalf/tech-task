<?php

namespace App\Http\Controllers;

use App\Application\User\Actions\CreateUserAction;
use App\Application\User\Actions\UpdateUserAction;
use App\Application\User\Actions\GetUserAction;
use App\Application\User\Actions\ListUsersAction;
use App\Application\User\Actions\DeleteUserAction;
use App\Application\Country\Actions\ListCountriesAction;

use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(
        private CreateUserAction $createUserAction,
        private UpdateUserAction $updateUserAction,
        private GetUserAction $getUserAction,
        private ListUsersAction $listUsersAction,
        private DeleteUserAction $deleteUserAction,
        private ListCountriesAction $listCountriesAction
    ) {}

    public function index()
    {
        $users = $this->listUsersAction->execute();
        $countries = $this->listCountriesAction->execute();
        return view('users.index', compact('users', 'countries'));
    }

    public function create()
    {
        $countries = $this->listCountriesAction->execute();
        return view('users.create', compact('countries'));
    }

    public function store(Request $request)
    {
        
        $this->createUserAction->execute($request);
        return redirect()->route('users.index');
    }

    public function show($id)
    {
        $user = $this->getUserAction->execute($id);

        if (!$user) {
            // 404 page
            return redirect()->route('users.index')
            ->withErrors(['User not found']);
        }

        $countries = $this->listCountriesAction->execute();
        return view('users.show', compact('user', 'countries'));
    }

    public function edit($id)
    {
        $user = $this->getUserAction->execute($id);
        if (!$user) {
            return redirect()->route('users.index')
                ->withErrors(['User not found with provided id:' . $id]);
        }
        $countries = $this->listCountriesAction->execute();
        return view('users.edit', compact('user', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $user = $this->updateUserAction->execute($id, $request);
        if (!$user) {
            return redirect()->route('users.index')
                ->withErrors(['User not found. Update action is failed for user id:' . $id]);
        }
        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        $this->deleteUserAction->execute($id);
        
        return redirect()->route('users.index');
    }
}
