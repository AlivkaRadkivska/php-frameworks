<?php

namespace App\Repositories;

use App\Models\ExamResult;
use App\Services\PaginateService;

class ExamResultRepository
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
        $query = ExamResult::query();

        if (!empty($filters['student_name'])) {
            $query->where('student_name', 'like', '%' . $filters['student_name'] . '%');
        }

        if (!empty($filters['exam_id'])) {
            $query->where('exam_id', $filters['exam_id']);
        }

        if (!empty($filters['obtained_grade'])) {
            $query->where('obtained_grade', $filters['obtained_grade']);
        }

        $p = $this->pagination->paginate($query, $perPage, $page);

        return [
            'items'           => $p->items(),
            'totalPageCount'  => $p->lastPage(),
            'totalItems'      => $p->total(),
        ];
    }
}
