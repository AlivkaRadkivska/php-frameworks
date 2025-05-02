<?php

namespace App\Repository;

use App\Entity\Exam;
use App\Service\PaginateService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exam>
 */
class ExamRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     * @param PaginateService $paginateService
     */
    public function __construct(ManagerRegistry $registry, PaginateService $paginateService)
    {
        parent::__construct($registry, Exam::class);
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
        $qb = $this->createQueryBuilder('exam');

        if (!empty($data['title'])) {
            $qb->andWhere('exam.title LIKE :title')
                ->setParameter('title', '%' . $data['title'] . '%');
        }

        if (!empty($data['type'])) {
            $qb->andWhere('exam.type = :type')
                ->setParameter('type', $data['type']);
        }

        if (!empty($data['courseId'])) {
            $qb->andWhere('exam.course = :courseId')
                ->setParameter('courseId', $data['courseId']);
        }

        return $this->paginateService->paginate($qb, $itemsPerPage, $page);
    }


}
