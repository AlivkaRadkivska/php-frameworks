<?php

namespace App\Controller;

use App\Entity\Group;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/group', name: 'group_routes')]
class GroupController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private GroupRepository $groupRepository;

    public function __construct(EntityManagerInterface $entityManager, GroupRepository $groupRepository)
    {
        $this->entityManager = $entityManager;
        $this->groupRepository = $groupRepository;
    }

    #[Route('/', name: 'get_groups', methods: ['GET'])]
    public function getGroups(): JsonResponse
    {
        $groups = $this->groupRepository->findAll();
        $data = array_map(fn(Group $group) => $group->jsonSerialize(), $groups);
        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/', name: 'create_group', methods: ['POST'])]
    public function createGroup(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $group = new Group();
        $group->setName($data['name'] ?? '');
        $group->setMajor($data['major'] ?? '');
        $group->setYear((int) ($data['year'] ?? 0));

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return new JsonResponse($group->jsonSerialize(), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'get_group', methods: ['GET'])]
    public function getGroup(int $id): JsonResponse
    {
        $group = $this->groupRepository->find($id);

        if (!$group) {
            return new JsonResponse(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($group->jsonSerialize(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'update_group', methods: ['PATCH'])]
    public function updateGroup(Request $request, int $id): JsonResponse
    {
        $group = $this->groupRepository->find($id);

        if (!$group) {
            return new JsonResponse(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $group->setName($data['name']);
        }

        if (isset($data['major'])) {
            $group->setMajor($data['major']);
        }

        if (isset($data['year'])) {
            $group->setYear((int) $data['year']);
        }

        $this->entityManager->flush();

        return new JsonResponse($group->jsonSerialize(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete_group', methods: ['DELETE'])]
    public function deleteGroup(int $id): JsonResponse
    {
        $group = $this->groupRepository->find($id);

        if (!$group) {
            return new JsonResponse(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($group);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Group deleted successfully'], Response::HTTP_OK);
    }
}
