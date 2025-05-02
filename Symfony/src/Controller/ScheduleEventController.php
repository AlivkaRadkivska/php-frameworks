<?php

namespace App\Controller;

use App\Entity\ScheduleEvent;
use App\Repository\ScheduleEventRepository;
use App\Repository\CourseRepository;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/schedule-event', name: 'schedule_event_routes')]
class ScheduleEventController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ScheduleEventRepository $scheduleEventRepository;
    private CourseRepository $courseRepository;
    private GroupRepository $groupRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ScheduleEventRepository $scheduleEventRepository,
        CourseRepository $courseRepository,
        GroupRepository $groupRepository
    ) {
        $this->entityManager = $entityManager;
        $this->scheduleEventRepository = $scheduleEventRepository;
        $this->courseRepository = $courseRepository;
        $this->groupRepository = $groupRepository;
    }

    #[Route('/', name: 'get_schedule_events', methods: ['GET'])]
    public function getScheduleEvents(): JsonResponse
    {
        $events = $this->scheduleEventRepository->findAll();
        $data = array_map(fn(ScheduleEvent $e) => $e->jsonSerialize(), $events);
        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/', name: 'create_schedule_event', methods: ['POST'])]
    public function createScheduleEvent(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['courseId'], $data['groupId'])) {
            return new JsonResponse(['message' => 'courseId and groupId are required'], Response::HTTP_BAD_REQUEST);
        }

        $course = $this->courseRepository->find($data['courseId']);
        if (!$course) {
            return new JsonResponse(['message' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }

        $group = $this->groupRepository->find($data['groupId']);
        if (!$group) {
            return new JsonResponse(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }

        $event = new ScheduleEvent();
        $event->setMeetingLink($data['meetingLink'] ?? '');
        $event->setStartDate(new \DateTime($data['startDate'] ?? 'now'));
        $event->setEndDate(new \DateTime($data['endDate'] ?? 'now'));
        $event->setCourse($course);
        $event->setGroup($group);

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return new JsonResponse($event->jsonSerialize(), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'get_schedule_event', methods: ['GET'])]
    public function getScheduleEvent(int $id): JsonResponse
    {
        $event = $this->scheduleEventRepository->find($id);

        if (!$event) {
            return new JsonResponse(['message' => 'Schedule event not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($event->jsonSerialize(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'update_schedule_event', methods: ['PATCH'])]
    public function updateScheduleEvent(Request $request, int $id): JsonResponse
    {
        $event = $this->scheduleEventRepository->find($id);

        if (!$event) {
            return new JsonResponse(['message' => 'Schedule event not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['meetingLink'])) {
            $event->setMeetingLink($data['meetingLink']);
        }

        if (isset($data['startDate'])) {
            $event->setStartDate(new \DateTime($data['startDate']));
        }

        if (isset($data['endDate'])) {
            $event->setEndDate(new \DateTime($data['endDate']));
        }

        if (isset($data['courseId'])) {
            $course = $this->courseRepository->find($data['courseId']);
            if (!$course) {
                return new JsonResponse(['message' => 'Course not found'], Response::HTTP_NOT_FOUND);
            }
            $event->setCourse($course);
        }

        if (isset($data['groupId'])) {
            $group = $this->groupRepository->find($data['groupId']);
            if (!$group) {
                return new JsonResponse(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
            }
            $event->setGroup($group);
        }

        $this->entityManager->flush();

        return new JsonResponse($event->jsonSerialize(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete_schedule_event', methods: ['DELETE'])]
    public function deleteScheduleEvent(int $id): JsonResponse
    {
        $event = $this->scheduleEventRepository->find($id);

        if (!$event) {
            return new JsonResponse(['message' => 'Schedule event not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($event);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Schedule event deleted successfully'], Response::HTTP_OK);
    }
}
