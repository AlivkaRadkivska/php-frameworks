<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginateService
{
    /**
     * @param Builder $query
     * @param int $perPage
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function paginate(Builder $query, int $perPage, int $page): LengthAwarePaginator
    {
        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
