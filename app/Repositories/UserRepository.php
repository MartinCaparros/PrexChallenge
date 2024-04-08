<?php

namespace App\Repositories;

use App\Models\User;

use App\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * The Admin instance.
     *
     * @var User
     */
    protected $model;

    public function __construct(User $user) {
        $this->model = $user;
    }

    /**
     * Store a new user
     *
     * @param array $data
     * @return mixed
     **/
    public function store(array $data)
    {
        return $this->model->create($data);
    }
}
