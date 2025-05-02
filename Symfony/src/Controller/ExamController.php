<?php

namespace App\Controller;

use App\Entity\Exam;
use App\Repository\ExamRepository;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/exam', name: 'exam_routes')]
class ExamController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ExamRepository $examRepository;
    private CourseRepository $courseRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ExamRepository $examRepository
     * @param CourseRepository $courseRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ExamRepository $examRepository,
        CourseRepository $courseRepository
    ) {
        $this->entityManager = $entityManager;
        $this->examRepository = $examRepository;
        $this->courseRepository = $courseRepository;
    }

    /**
     * Get all exams.
     *
     * @return JsonResponse
     */
    #[Route('/', name: 'get_exams', methods: ['GET'])]
    public function getExams(): JsonResponse
    {
        $exams = $this->examRepository->findAll();
        $data = array_map(fn(Exam $exam) => $exam->jsonSerialize(), $exams);
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * Create a new exam.
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'create_exam', methods: ['POST'])]
    public function createExam(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['courseId'])) {
            return new JsonResponse(['message' => 'courseId is required'], Response::HTTP_BAD_REQUEST);
        }

        $course = $this->courseRepository->find($data['courseId']);
        if (!$course) {
            return new JsonResponse(['message' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }

        $exam = new Exam();
        $exam->setTitle($data['title'] ?? '');
        $exam->setType($data['type'] ?? '');
        $exam->setDuration($data['duration'] ?? '');
        $exam->setMaxGrade((int) ($data['maxGrade'] ?? 0));
        $exam->setStartDate(new \DateTime($data['startDate'] ?? 'now'));
        $exam->setCourse($course);

        $this->entityManager->persist($exam);
        $this->entityManager->flush();

        return new JsonResponse($exam->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * Get an exam by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_exam', methods: ['GET'])]
    public function getExam(int $id): JsonResponse
    {
        $exam = $this->examRepository->find($id);

        if (!$exam) {
            return new JsonResponse(['message' => 'Exam not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($exam->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * Update an exam by ID.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'update_exam', methods: ['PATCH'])]
    public function updateExam(Request $request, int $id): JsonResponse
    {
        $exam = $this->examRepository->find($id);

        if (!$exam) {
            return new JsonResponse(['message' => 'Exam not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['title'])) {
            $exam->setTitle($data['title']);
        }

        if (isset($data['type'])) {
            $exam->setType($data['type']);
        }

        if (isset($data['duration'])) {
            $exam->setDuration($data['duration']);
        }

        if (isset($data['maxGrade'])) {
            $exam->setMaxGrade((int) $data['maxGrade']);
        }

        if (isset($data['startDate'])) {
            $exam->setStartDate(new \DateTime($data['startDate']));
        }

        if (isset($data['courseId'])) {
            $course = $this->courseRepository->find($data['courseId']);
            if (!$course) {
                return new JsonResponse(['message' => 'Course not found'], Response::HTTP_NOT_FOUND);
            }
            $exam->setCourse($course);
        }

        $this->entityManager->flush();

        return new JsonResponse($exam->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * Delete an exam by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_exam', methods: ['DELETE'])]
    public function deleteExam(int $id): JsonResponse
    {
        $exam = $this->examRepository->find($id);

        if (!$exam) {
            return new JsonResponse(['message' => 'Exam not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($exam);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Exam deleted successfully'], Response::HTTP_OK);
    }
}
