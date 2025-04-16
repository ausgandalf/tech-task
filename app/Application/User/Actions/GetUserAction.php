<?php

namespace App\Application\User\Actions;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Entities\User;

class GetUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute($userId): ?User
    {   
        $userId = intval($userId);
        if ($userId == 0) return null;
        return $this->userRepository->find($userId);
    }
}
