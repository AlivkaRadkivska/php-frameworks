<?php

namespace App\Repository;

use App\Entity\Course;
use App\Service\PaginateService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Course>
 */
class CourseRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     * @param PaginateService $paginateService
     */
    public function __construct(ManagerRegistry $registry, PaginateService $paginateService)
    {
        parent::__construct($registry, Course::class);
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
        $queryBuilder = $this->createQueryBuilder('course');

        if (!empty($data['name'])) {
            $queryBuilder->andWhere('course.name LIKE :name')
                ->setParameter('name', '%' . $data['name'] . '%');
        }

        if (!empty($data['credits'])) {
            $queryBuilder->andWhere('course.credits = :credits')
                ->setParameter('credits', $data['credits']);
        }

        return $this->paginateService->paginate($queryBuilder, $itemsPerPage, $page);
    }

}
