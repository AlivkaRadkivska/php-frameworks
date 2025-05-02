<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ExamController extends Controller
{
    /**
     * Display a listing of the exams.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $exams = Exam::all();
        return response()->json($exams, Response::HTTP_OK);
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
