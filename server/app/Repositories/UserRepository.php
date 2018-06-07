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
        if (is_null($user = $this->model->find($openid))) {
            $user = $this->model->newInstance();
            $user->openid = $openid;
            $user->save();
        }
        return $this->model->findOrFail($openid);
    }
}
