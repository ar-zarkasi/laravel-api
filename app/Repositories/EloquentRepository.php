<?php

namespace App\Repositories;

use App\Interfaces\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Log;

abstract class EloquentRepository implements BaseRepository
{
    protected $model;

    protected $withoutGlobalScopes = false;

    protected $with = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function with(array $with = []): BaseRepository
    {
        $this->with = $with;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withoutGlobalScopes(): BaseRepository
    {
        $this->withoutGlobalScopes = true;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function store(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Model $model, array $data): Model
    {
        $updated = tap($model)->update($data);
        Cache::forget(sprintf('%s:%s', class_basename($this->model), $model->id));
        return $updated;
    }

    /**
     * {@inheritdoc}
     */
    public function findByFilters(): Collection
    {
        return $this->model->with($this->with)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function findByFiltersPaginate($filters = [], $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with($this->with);
        foreach ($filters as $key => $filter) {
            if(is_scalar($filter))
                $query->where($key, $filter);
            else if(isset($filter['key']) && isset($filter['operand']) && isset($filter['value']))
                $query->where($filter['key'], $filter['operand'], $filter['value']);
            else if(isset($filter['key']) && isset($filter['value']))
                $query->where($filter['key'], $filter['value']);
        }
        return $query->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneById(string $id): Model
    {
        return Cache::remember(sprintf('%s:%s', class_basename($this->model), $id), Carbon::now()->addHour(24), function () use ($id) {
            return $this->findOneBy(['id' => $id]);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(array $criteria): Model
    {
        if (!$this->withoutGlobalScopes) {
            return $this->model->with($this->with)
                ->where($criteria)
                // ->orderByDesc('created_at')
                ->firstOrNew();
        }

        return $this->model->with($this->with)
            ->withoutGlobalScopes()
            ->where($criteria)
            // ->orderByDesc('created_at')
            ->firstOrNew();
    }

    /**
     * {@inheritdoc}
     */
    public function findOneOrCreateBy(array $criteria, array $attribute = []): Model
    {
        return $this->model->firstOrCreate($criteria, $attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function insertGetId(array $criteria): int
    {
        return $this->model->insertGetId($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function destroy(string $id): int
    {
        // return $this->findOneBy(['id' => $id])->delete();
        return $this->model->destroy($id);
    }

    /**
     * {@inheritdoc}
     */
    public function max(string $criteria): int
    {
        $el = $this->model->max($criteria);
        return is_null($el) ? 0 : $el;
    }
}
