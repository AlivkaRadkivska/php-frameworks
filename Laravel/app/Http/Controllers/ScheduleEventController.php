<?php

namespace App\Http\Controllers;

use App\Models\ScheduleEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ScheduleEventController extends Controller
{
    /**
     * Display a listing of the schedule events.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $events = ScheduleEvent::all();
        return response()->json($events, Response::HTTP_OK);
    }

    /**
     * Store a newly created schedule event.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'meeting_link' => 'required|string|max:255',
            'course_id' => 'required|integer|exists:courses,id',
            'group_id' => 'required|integer|exists:groups,id',
        ]);

        $event = ScheduleEvent::create($validated);

        return response()->json($event, Response::HTTP_CREATED);
    }

    /**
     * Display the specified schedule event.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $event = ScheduleEvent::find($id);

        if (!$event) {
            return response()->json(['message' => 'Schedule event not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($event, Response::HTTP_OK);
    }

    /**
     * Update the specified schedule event.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $event = ScheduleEvent::find($id);

        if (!$event) {
            return response()->json(['message' => 'Schedule event not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'meeting_link' => 'required|string|max:255',
            'course_id' => 'required|integer|exists:courses,id',
            'group_id' => 'required|integer|exists:groups,id',
        ]);

        $event->update($validated);

        return response()->json($event, Response::HTTP_OK);
    }

    /**
     * Remove the specified schedule event.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $event = ScheduleEvent::find($id);

        if (!$event) {
            return response()->json(['message' => 'Schedule event not found'], Response::HTTP_NOT_FOUND);
        }

        $event->delete();

        return response()->json(['message' => 'Schedule event deleted successfully'], Response::HTTP_OK);
    }
}
