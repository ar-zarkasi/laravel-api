<?php

namespace App\Repositories\Authentication;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Requests\UserRequest;
use App\Interfaces\Authentication\UserInterface;
use App\Repositories\EloquentRepository;
use App\Models\User;
use DB;

class UserRepository extends EloquentRepository implements UserInterface
{
    protected $with = [];

    public function getAllUsers() : Collection
    {
        return $this->model
            ->with([])
            ->get();
    }

    public function getUserById($id): Model
    {
        return $this->model
            ->with([])
            ->where('id', $id)
            ->where('active', 1)
            ->firstOrNew();
    }

    public function getFilteredUsers($params): Collection
    {
        $el = $this->model
            ->with([]);
        
        foreach ($params as $key => $value) {
            $el->where($key, $value);
        }

        return $el->get();
    }
}