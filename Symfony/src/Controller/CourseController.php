<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/course', name: 'course_routes')]
class CourseController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CourseRepository $courseRepository;

    public function __construct(EntityManagerInterface $entityManager, CourseRepository $courseRepository)
    {
        $this->entityManager = $entityManager;
        $this->courseRepository = $courseRepository;
    }

    #[Route('/', name: 'get_courses', methods: ['GET'])]
    public function getCourses(): JsonResponse
    {
        $courses = $this->courseRepository->findAll();
        $data = array_map(fn(Course $course) => $course->jsonSerialize(), $courses);
        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/', name: 'create_course', methods: ['POST'])]
    public function createCourse(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $course = new Course();
        $course->setName($data['name'] ?? '');
        $course->setDescription($data['description'] ?? '');
        $course->setCredits($data['credits'] ?? '');

        $this->entityManager->persist($course);
        $this->entityManager->flush();

        return new JsonResponse($course->jsonSerialize(), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'get_course', methods: ['GET'])]
    public function getCourse(int $id): JsonResponse
    {
        $course = $this->courseRepository->find($id);

        if (!$course) {
            return new JsonResponse(['message' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($course->jsonSerialize(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'update_course', methods: ['PATCH'])]
    public function updateCourse(Request $request, int $id): JsonResponse
    {
        $course = $this->courseRepository->find($id);

        if (!$course) {
            return new JsonResponse(['message' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $course->setName($data['name']);
        }

        if (isset($data['description'])) {
            $course->setDescription($data['description']);
        }

        if (isset($data['credits'])) {
            $course->setCredits($data['credits']);
        }

        $this->entityManager->flush();

        return new JsonResponse($course->jsonSerialize(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete_course', methods: ['DELETE'])]
    public function deleteCourse(int $id): JsonResponse
    {
        $course = $this->courseRepository->find($id);

        if (!$course) {
            return new JsonResponse(['message' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($course);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Course deleted successfully'], Response::HTTP_OK);
    }
}