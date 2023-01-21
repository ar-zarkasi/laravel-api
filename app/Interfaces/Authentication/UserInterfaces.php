<?php namespace App\Interfaces\Authentication;

use App\Interfaces\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface UserInterface extends BaseRepository
{
    public function getAllUsers(): Collection;
    public function getUserById(int $id): Model;
    public function getUserByEmail(string $id): Model;
    public function getUserByPhone(string $id): Model;
    public function getUserByUsername(string $id): Model;
    public function getFilteredUsers($params): Collection;
}