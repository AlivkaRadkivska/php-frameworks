<?php

namespace App\Repository;

use App\Entity\ExamResult;
use App\Service\PaginateService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExamResult>
 */
class ExamResultRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     * @param PaginateService $paginateService
     */
    public function __construct(ManagerRegistry $registry, PaginateService $paginateService)
    {
        parent::__construct($registry, ExamResult::class);
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
        $qb = $this->createQueryBuilder('er');

        if (!empty($data['studentName'])) {
            $qb->andWhere('er.studentName LIKE :studentName')
                ->setParameter('studentName', '%' . $data['studentName'] . '%');
        }

        if (!empty($data['examId'])) {
            $qb->andWhere('er.exam = :examId')
                ->setParameter('examId', $data['examId']);
        }

        return $this->paginateService->paginate($qb, $itemsPerPage, $page);
    }
}
