<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\FreightRequest;
use App\Http\Requests\FreightUpdateRequest;
use App\Models\Freight;
use App\Services\FreightService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class FreightController extends Controller
{
    protected FreightService $freightService;

    public function __construct(FreightService $freightService)
    {
        $this->freightService = $freightService;
    }

    /**
     * Store a new freight record.
     *
     * @param FreightRequest $request
     * @return JsonResponse
     */
    public function store(FreightRequest $request)
    {
        // The validated method returns an array of the validated data
        $freight = $this->freightService->create($request->validated());
        return $freight;
    }

    /**
     * Update an existing freight record using its UUID.
     *
     * @param FreightRequest $request
     * @param string $uuid The UUID of the freight to update.
     * @return JsonResponse
     */
    public function update(FreightUpdateRequest $request, string $uuid)
    {
        // First, check if the freight record exists
        $freight = $this->freightService->getFreightByUuid($uuid);
        if (!$freight) {
            return response()->json(['message' => 'Freight not found'], 404);
        }

        // Check if the current authenticated user is the load owner of this freight
        if (Auth::id() !== $freight->load_owner_id) {
            return response()->json(['message' => 'You are not authorized to update this freight.'], 403);
        }

        // Pass the UUID directly to the service layer for updating
        $response = $this->freightService->updateFreight($uuid, $request->validated());
        return $response;
    }

    /**
     * Delete a freight record using its UUID.
     *
     * @param string $uuid The UUID of the freight to delete.
     * @return JsonResponse
     */
    public function destroy(string $uuid)
    {
        // First, check if the freight record exists
        $freight = $this->freightService->getFreightByUuid($uuid);
        if (!$freight) {
            return response()->json(['message' => 'Freight not found'], 404);
        }

        // Check if the current authenticated user is the creator of this freight
        if (Auth::id() !== $freight->load_owner_id) {
            return response()->json(['message' => 'You are not authorized to delete this freight.'], 403);
        }

        // Pass the UUID directly to the service layer for deletion
        $response = $this->freightService->deleteFreight($uuid);
        return $response;
    }

    /**
     * Display a single freight record using its UUID.
     *
     * @param string $uuid The UUID of the freight to retrieve.
     * @return JsonResponse
     */
    public function show(string $uuid)
    {
        // Pass the UUID directly to the service layer to get the record
        $freight = $this->freightService->getFreightByUuid($uuid);
        if (!$freight) {
            return response()->json(['message' => 'Freight not found'], 404);
        }

        // // Check if the current authenticated user is the creator of this freight
        // if (Auth::id() !== $freight->load_owner_id) {
        //     return response()->json(['message' => 'You are not authorized to view this freight.'], 403);
        // }

        // Check if the service returned a valid record before returning a JSON response
        return response()->json($freight);
    }

    /**
     * Display a list of all freight records.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $freights = $this->freightService->getAllFreights();
        return response()->json($freights);
    }

    /**
     * Search for freight records.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchFreight(Request $request)
    {
        $query = $request->input('query');
        $freights = $this->freightService->searchFreights($query);
        return response()->json($freights);
    }
}
