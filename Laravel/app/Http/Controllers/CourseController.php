<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Repositories\CourseRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CourseController extends Controller
{
    public const ITEMS_PER_PAGE = 5;

    /**
     * @var CourseRepository
     */
    private CourseRepository $repo;

    /**
     * @param CourseRepository $repo
     */
    public function __construct(CourseRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the courses.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['name', 'description', 'credits']);
        $perPage = (int) $request->query('itemsPerPage', self::ITEMS_PER_PAGE);
        $page    = (int) $request->query('page', 1);

        $data = $this->repo->getAllByFilter($filters, $perPage, $page);

        return response()->json($data, JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created course.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'credits' => 'nullable|string|max:10',
        ]);

        $course = Course::create($validated);

        return response()->json($course, Response::HTTP_CREATED);
    }

    /**
     * Display the specified course.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($course, Response::HTTP_OK);
    }

    /**
     * Update the specified course.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'credits' => 'nullable|string|max:10',
        ]);

        $course->update($validated);

        return response()->json($course, Response::HTTP_OK);
    }

    /**
     * Remove the specified course.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }

        $course->delete();

        return response()->json(['message' => 'Course deleted successfully'], Response::HTTP_OK);
    }
}
