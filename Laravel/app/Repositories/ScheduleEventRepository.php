<?php

namespace App\Repositories;

use App\Models\ScheduleEvent;
use App\Services\PaginateService;

class ScheduleEventRepository
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
        $query = ScheduleEvent::query();

        if (!empty($filters['start_date'])) {
            $query->whereDate('start_date', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('end_date', $filters['end_date']);
        }

        if (!empty($filters['meeting_link'])) {
            $query->where('meeting_link', 'like', '%' . $filters['meeting_link'] . '%');
        }

        if (!empty($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        if (!empty($filters['group_id'])) {
            $query->where('group_id', $filters['group_id']);
        }

        $p = $this->pagination->paginate($query, $perPage, $page);

        return [
            'items' => $p->items(),
            'totalPageCount' => $p->lastPage(),
            'totalItems' => $p->total(),
        ];
    }
}
