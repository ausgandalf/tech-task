<?php

namespace App\Infrastructure\Persistence;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Entities\User;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $model, array $data): User
    {
        return $model->update($data);
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function delete(int $id): void
    {
        User::delete($id);
    }

    public function all(): array
    {
        return User::all();
    }
}
