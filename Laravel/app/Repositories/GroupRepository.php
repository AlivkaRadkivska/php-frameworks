<?php

namespace App\Repositories;

use App\Models\Group;
use App\Services\PaginateService;

class GroupRepository
{
    /**
     * @var PaginateService
     */
    private PaginateService $pagination;

    /**
     * @param PaginateService $pagination
     */
    public function __construct(PaginateService $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * @param array $filters
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function getAllByFilter(array $filters, int $perPage, int $page): array
    {
        $query = Group::query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['major'])) {
            $query->where('major', 'like', '%' . $filters['major'] . '%');
        }

        if (!empty($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        $p = $this->pagination->paginate($query, $perPage, $page);

        return [
            'items' => $p->items(),
            'totalPageCount' => $p->lastPage(),
            'totalItems' => $p->total(),
        ];
    }
}
