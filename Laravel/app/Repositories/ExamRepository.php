<?php

namespace App\Repositories;

use App\Models\Exam;
use App\Services\PaginateService;

class ExamRepository
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
        $query = Exam::query();

        if (!empty($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        if (!empty($filters['type'])) {
            $query->where('type', 'like', '%' . $filters['type'] . '%');
        }

        if (!empty($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('start_date', $filters['start_date']);
        }

        $p = $this->pagination->paginate($query, $perPage, $page);

        return [
            'items' => $p->items(),
            'totalPageCount' => $p->lastPage(),
            'totalItems' => $p->total(),
        ];
    }
}
