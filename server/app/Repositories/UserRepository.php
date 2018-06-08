<?php

namespace App\Repositories;

use Exception;
use App\Models\User;

class UserRepository
{
    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Get the record.
     *
     * @param  int $id
     * @return App\Models\User
     */
    public function getById($openid)
    {
        return $this->model->firstOrCreate([ 'openid' => $openid ]);
    }
}
