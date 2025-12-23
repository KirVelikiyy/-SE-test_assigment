<?php

namespace App\Repositories;

use App\Exceptions\Users\UserNotFound;
use App\Models\User;

class UserRepository
{
    protected function query(): \Illuminate\Database\Eloquent\Builder
    {
        return (new User())->newQuery();
    }

    public function getUserById(int $userId): User
    {
        $user = $this->query()->find($userId);
        if (!$user) {
            throw new UserNotFound();
        }

        return $user;
    }
}

