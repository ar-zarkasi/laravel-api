<?php namespace App\Interfaces\Authentication;

use App\Interfaces\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface TokenInterface extends BaseRepository
{
    public function getTokenByUser(int $id): Model;
    public function getUserByToken(string $token): Model;
}