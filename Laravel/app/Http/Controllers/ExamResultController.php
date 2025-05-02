<?php

namespace App\Http\Controllers;

use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ExamResultController extends Controller
{
    /**
     * Display a listing of the exam results.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $results = ExamResult::all();
        return response()->json($results, Response::HTTP_OK);
    }

    /**
     * Store a newly created exam result.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'answer' => 'required|string|max:1000',
            'obtained_grade' => 'required|integer',
            'exam_id' => 'required|integer|exists:exams,id',
        ]);

        $result = ExamResult::create($validated);

        return response()->json($result, Response::HTTP_CREATED);
    }

    /**
     * Display the specified exam result.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $result = ExamResult::find($id);

        if (!$result) {
            return response()->json(['message' => 'Exam result not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($result, Response::HTTP_OK);
    }

    /**
     * Update the specified exam result.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $result = ExamResult::find($id);

        if (!$result) {
            return response()->json(['message' => 'Exam result not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'answer' => 'required|string|max:1000',
            'obtained_grade' => 'required|integer',
            'exam_id' => 'required|integer|exists:exams,id',
        ]);

        $result->update($validated);

        return response()->json($result, Response::HTTP_OK);
    }

    /**
     * Remove the specified exam result.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = ExamResult::find($id);

        if (!$result) {
            return response()->json(['message' => 'Exam result not found'], Response::HTTP_NOT_FOUND);
        }

        $result->delete();

        return response()->json(['message' => 'Exam result deleted successfully'], Response::HTTP_OK);
    }
}
