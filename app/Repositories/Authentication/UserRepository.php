<?php

namespace App\Repositories\Authentication;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

use App\Interfaces\Authentication\UserInterface;
use App\Repositories\EloquentRepository;

class UserRepository extends EloquentRepository implements UserInterface
{
    protected $with = ['getAuth','getRoles'];

    public function getAllUsers() : Collection
    {
        return $this->model
            ->with($this->with)
            ->get();
    }

    public function getUserById(int $id): Model
    {
        return $this->model
            ->with($this->with)
            ->where('id', $id)
            ->where('active', 1)
            ->firstOrNew();
    }

    public function getUserByEmail(string $id): Model
    {
        return $this->model
            ->with($this->with)
            ->where('email', $id)
            ->firstOrNew();
    }

    public function getUserByPhone(string $id): Model
    {
        return $this->model
            ->with($this->with)
            ->where('phone', $id)
            ->firstOrNew();
    }

    public function getUserByUsername(string $id): Model
    {
        return $this->model
            ->with($this->with)
            ->where('username', $id)
            ->firstOrNew();
    }

    public function getFilteredUsers($params): Collection
    {
        $el = $this->model
            ->with($this->with);
        
        foreach ($params as $key => $value) {
            $el->where($key, $value);
        }

        return $el->get();
    }
}