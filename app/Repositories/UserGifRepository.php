<?php

namespace App\Repositories;

use App\Models\UserGif;

use App\Interfaces\UserGifRepositoryInterface;


class UserGifRepository implements UserGifRepositoryInterface
{
    /**
     * The Admin instance.
     *
     * @var UserGif
     */
    protected $model;

    public function __construct(UserGif $gif) {
        $this->model = $gif;
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
