<?php
namespace App\Repositories\Authentication;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

use App\Interfaces\Authentication\TokenInterface;
use App\Repositories\EloquentRepository;

class TokenRepository extends EloquentRepository implements TokenInterface
{
    protected $with = ['getUser', 'getUser.roles'];

    public function getTokenByUser(int $id): Model
    {
        return $this->model
            ->with($this->with)
            ->where('id_user')
            ->firstOrNew();
    }

    public function getUserByToken(string $token): Model
    {
        $token = str_replace('Bearer ',"",$token);
        return $this->model
            ->with($this->with)
            ->where('token', $token)
            ->firstOrNew();
    }
}