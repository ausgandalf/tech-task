<?php

namespace App\Application\User\Actions;

use App\Domain\User\Repositories\UserRepositoryInterface;

class ListUsersAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(): array
    {
        return $this->userRepository->all();
    }
}
