<?php

namespace App\Repository;

use App\Entity\ScheduleEvent;
use App\Service\PaginateService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ScheduleEvent>
 */
class ScheduleEventRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     * @param PaginateService $paginateService
     */
    public function __construct(ManagerRegistry $registry, PaginateService $paginateService)
    {
        parent::__construct($registry, ScheduleEvent::class);
        $this->paginateService = $paginateService;
    }

    /**
     * @param array $data
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $qb = $this->createQueryBuilder('se');

        if (!empty($data['meetingLink'])) {
            $qb->andWhere('se.meetingLink LIKE :link')
                ->setParameter('link', '%' . $data['meetingLink'] . '%');
        }

        if (!empty($data['courseId'])) {
            $qb->andWhere('se.course = :courseId')
                ->setParameter('courseId', $data['courseId']);
        }

        if (!empty($data['groupId'])) {
            $qb->andWhere('se.group = :groupId')
                ->setParameter('groupId', $data['groupId']);
        }

        return $this->paginateService->paginate($qb, $itemsPerPage, $page);
    }
}
