<?php

namespace App\Controller;

use App\Entity\ExamResult;
use App\Repository\ExamResultRepository;
use App\Repository\ExamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/exam-result', name: 'exam_result_routes')]
class ExamResultController extends AbstractController
{
    public const ITEMS_PER_PAGE = 5;
    private EntityManagerInterface $entityManager;
    private ExamResultRepository $examResultRepository;
    private ExamRepository $examRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ExamResultRepository $examResultRepository
     * @param ExamRepository $examRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ExamResultRepository $examResultRepository,
        ExamRepository $examRepository
    ) {
        $this->entityManager = $entityManager;
        $this->examResultRepository = $examResultRepository;
        $this->examRepository = $examRepository;
    }

    /**
     * Get all exam results.
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'get_exam_results', methods: ['GET'])]
    public function getExamResults(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $itemsPerPage = (int) ($requestData['itemsPerPage'] ?? self::ITEMS_PER_PAGE);
        $page = (int) ($requestData['page'] ?? 1);
        $data = $this->examResultRepository->getAllByFilter($requestData, $itemsPerPage, $page);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * Create a new exam result.
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'create_exam_result', methods: ['POST'])]
    public function createExamResult(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['examId'])) {
            return new JsonResponse(['message' => 'examId is required'], Response::HTTP_BAD_REQUEST);
        }

        $exam = $this->examRepository->find($data['examId']);
        if (!$exam) {
            return new JsonResponse(['message' => 'Exam not found'], Response::HTTP_NOT_FOUND);
        }

        $result = new ExamResult();
        $result->setStudentName($data['studentName'] ?? '');
        $result->setAnswer($data['answer'] ?? '');
        $result->setObtainedGrade((int) ($data['obtainedGrade'] ?? 0));
        $result->setExam($exam);

        $this->entityManager->persist($result);
        $this->entityManager->flush();

        return new JsonResponse($result->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * Get an exam result by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_exam_result', methods: ['GET'])]
    public function getExamResult(int $id): JsonResponse
    {
        $result = $this->examResultRepository->find($id);

        if (!$result) {
            return new JsonResponse(['message' => 'Exam result not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($result->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * Update an exam result by ID.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'update_exam_result', methods: ['PATCH'])]
    public function updateExamResult(Request $request, int $id): JsonResponse
    {
        $result = $this->examResultRepository->find($id);

        if (!$result) {
            return new JsonResponse(['message' => 'Exam result not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['studentName'])) {
            $result->setStudentName($data['studentName']);
        }

        if (isset($data['answer'])) {
            $result->setAnswer($data['answer']);
        }

        if (isset($data['obtainedGrade'])) {
            $result->setObtainedGrade((int) $data['obtainedGrade']);
        }

        if (isset($data['examId'])) {
            $exam = $this->examRepository->find($data['examId']);
            if (!$exam) {
                return new JsonResponse(['message' => 'Exam not found'], Response::HTTP_NOT_FOUND);
            }
            $result->setExam($exam);
        }

        $this->entityManager->flush();

        return new JsonResponse($result->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * Delete an exam result by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_exam_result', methods: ['DELETE'])]
    public function deleteExamResult(int $id): JsonResponse
    {
        $result = $this->examResultRepository->find($id);

        if (!$result) {
            return new JsonResponse(['message' => 'Exam result not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($result);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Exam result deleted successfully'], Response::HTTP_OK);
    }
}
