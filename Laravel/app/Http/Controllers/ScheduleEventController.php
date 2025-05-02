<?php

namespace App\Http\Controllers;

use App\Models\ScheduleEvent;
use App\Repositories\ScheduleEventRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ScheduleEventController extends Controller
{
    public const ITEMS_PER_PAGE = 5;

    /**
     * @var ScheduleEventRepository
     */
    private ScheduleEventRepository $repo;

    /**
     * @param ScheduleEventRepository $repo
     */
    public function __construct(ScheduleEventRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the schedule events.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['start_date', 'end_date', 'meeting_link', 'course_id', 'group_id']);
        $perPage = (int) $request->query('itemsPerPage', self::ITEMS_PER_PAGE);
        $page    = (int) $request->query('page', 1);

        $data = $this->repo->getAllByFilter($filters, $perPage, $page);

        return response()->json($data, JsonResponse::HTTP_OK);
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
