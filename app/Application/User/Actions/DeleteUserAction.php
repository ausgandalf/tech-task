<?php

namespace App\Application\User\Actions;

use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class DeleteUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(int $userId): void
    {
        $user = $this->userRepository->find($userId);
        if (!$user) {
            // No user found ???
            return;
        }

        $this->userRepository->delete($userId);
        // Remove selfie from DB
        if ($user->selfie) {
            Storage::disk('public')->delete($user->selfie);
        }
    }
}
