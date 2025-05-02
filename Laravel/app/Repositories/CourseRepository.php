<?php

namespace App\Repositories;

use App\Models\Course;
use App\Services\PaginateService;

class CourseRepository
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
        $query = Course::query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        if (!empty($filters['description'])) {
            $query->where('description', 'like', '%'.$filters['description'].'%');
        }

        if (!empty($filters['credits'])) {
            $query->where('credits', $filters['credits']);
        }

        $p = $this->pagination->paginate($query, $perPage, $page);

        return [
            'items'           => $p->items(),
            'totalPageCount'  => $p->lastPage(),
            'totalItems'      => $p->total(),
        ];
    }
}
