<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Repositories\GroupRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class GroupController extends Controller
{
    public const ITEMS_PER_PAGE = 5;

    /**
     * @var GroupRepository
     */
    private GroupRepository $repo;

    /**
     * @param GroupRepository $repo
     */
    public function __construct(GroupRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the groups.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['name', 'major', 'year']);
        $perPage = (int) $request->query('itemsPerPage', self::ITEMS_PER_PAGE);
        $page    = (int) $request->query('page', 1);

        $data = $this->repo->getAllByFilter($filters, $perPage, $page);

        return response()->json($data, JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created group.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'year' => 'required|integer',
        ]);

        $group = Group::create($validated);

        return response()->json($group, Response::HTTP_CREATED);
    }

    /**
     * Display the specified group.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $group = Group::find($id);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($group, Response::HTTP_OK);
    }

    /**
     * Update the specified group.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $group = Group::find($id);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'year' => 'required|integer',
        ]);

        $group->update($validated);

        return response()->json($group, Response::HTTP_OK);
    }

    /**
     * Remove the specified group.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $group = Group::find($id);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }

        $group->delete();

        return response()->json(['message' => 'Group deleted successfully'], Response::HTTP_OK);
    }
}
