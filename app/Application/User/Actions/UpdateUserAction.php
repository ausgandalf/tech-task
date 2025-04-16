<?php

namespace App\Application\User\Actions;

use Illuminate\Http\Request;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Entities\User;
use Illuminate\Support\Facades\Storage;

class UpdateUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(int $userId, Request $request, bool $isPut = false): User
    {
        $user = $this->userRepository->find($userId);
        if (!$user) {
            // No user found ???
            return null;
        }

        $input = [];
        if ($isPut) {
            $input = $request->validate([
                'name' => 'nullable|string|max:255',
                'surname' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255|unique:users,email,' . $userId,
                'phone' => 'nullable|string|max:20',
                'country' => 'nullable|string',
                'gender' => 'nullable|string',
                'password' => 'nullable|string|min:8|confirmed',
                'introduction' => 'nullable|string',
                'selfie' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                'delete_selfie' => 'nullable|string',
            ]);
        } else {
            $input = $request->validate([
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
                'phone' => 'nullable|string|max:20',
                'country' => 'required|string',
                'gender' => 'required|string',
                'password' => 'nullable|string|min:8|confirmed',
                'introduction' => 'nullable|string',
                'selfie' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                'delete_selfie' => 'nullable|string',
            ]);
        }
        
        // Handle file upload
        $selfieToRemove = '';
        if ($request->hasFile('selfie')) {
            $path = $request->file('selfie')->store('selfies', 'public');
            $input['selfie'] = $path;
            // Lets remove old selfie
            $selfieToRemove = $user->selfie;
        } else {
            unset($input['selfie']);
        }

        // Remove selfie from DB
        if (isset($input['delete_selfie']) && ($input['delete_selfie'] == '1')) {
            $input['selfie'] = '';
            $selfieToRemove = $user->selfie;
        }

        // Remove selfie from storage
        if ($selfieToRemove) {
            Storage::disk('public')->delete($selfieToRemove);
        }

        // Update user in the database
        return $this->userRepository->update($user, $input);
    }
}


