<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Repositories\ExamRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExamController extends Controller
{
    public const ITEMS_PER_PAGE = 5;

    /**
     * @var ExamRepository
     */
    private ExamRepository $repo;

    /**
     * @param ExamRepository $repo
     */
    public function __construct(ExamRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the exams.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['title', 'type', 'course_id', 'start_date']);
        $perPage = (int) $request->query('itemsPerPage', self::ITEMS_PER_PAGE);
        $page    = (int) $request->query('page', 1);

        $data = $this->repo->getAllByFilter($filters, $perPage, $page);

        return response()->json($data, JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created exam.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'max_grade' => 'required|integer',
            'start_date' => 'required|date',
            'course_id' => 'required|integer|exists:courses,id',
        ]);

        $exam = Exam::create($validated);

        return response()->json($exam, Response::HTTP_CREATED);
    }

    /**
     * Display the specified exam.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $exam = Exam::find($id);

        if (!$exam) {
            return response()->json(['message' => 'Exam not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($exam, Response::HTTP_OK);
    }

    /**
     * Update the specified exam.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $exam = Exam::find($id);

        if (!$exam) {
            return response()->json(['message' => 'Exam not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'max_grade' => 'required|integer',
            'start_date' => 'required|date',
            'course_id' => 'required|integer|exists:courses,id',
        ]);

        $exam->update($validated);

        return response()->json($exam, Response::HTTP_OK);
    }

    /**
     * Remove the specified exam.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $exam = Exam::find($id);

        if (!$exam) {
            return response()->json(['message' => 'Exam not found'], Response::HTTP_NOT_FOUND);
        }

        $exam->delete();

        return response()->json(['message' => 'Exam deleted successfully'], Response::HTTP_OK);
    }
}
