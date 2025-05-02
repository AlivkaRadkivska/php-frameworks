<?php

namespace App\Repository;

use App\Entity\Group;
use App\Service\PaginateService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Group>
 */
class GroupRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     * @param PaginateService $paginateService
     */
    public function __construct(ManagerRegistry $registry, PaginateService $paginateService)
    {
        parent::__construct($registry, Group::class);
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
        $qb = $this->createQueryBuilder('g');

        if (!empty($data['name'])) {
            $qb->andWhere('g.name LIKE :name')
                ->setParameter('name', '%' . $data['name'] . '%');
        }

        if (!empty($data['major'])) {
            $qb->andWhere('g.major LIKE :major')
                ->setParameter('major', '%' . $data['major'] . '%');
        }

        if (!empty($data['year'])) {
            $qb->andWhere('g.year = :year')
                ->setParameter('year', $data['year']);
        }

        return $this->paginateService->paginate($qb, $itemsPerPage, $page);
    }
}
