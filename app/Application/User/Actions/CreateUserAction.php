<?php

namespace App\Application\User\Actions;

use Illuminate\Http\Request;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Entities\User;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(Request $request): User
    {
        $user = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'country' => 'required|string',
            'gender' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'introduction' => 'nullable|string',
            'selfie' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('selfie')) {
            $path = $request->file('selfie')->store('selfies', 'public');
            $user['selfie'] = $path;
        } else {
            unset($user['selfie']);
        }

        return $this->userRepository->create($user);
    }
}

