<?php

namespace App\Domains\Freight;

use App\Http\Controllers\Controller;
use App\Domains\Freight\FreightRequest;
use App\Domains\Freight\UpdateFreightRequest;
use App\Domains\Freight\Actions\CreateFreight;
use App\Domains\Freight\Actions\UpdateFreight;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponse;


class FreightController extends Controller
{
    use ApiResponse;

    public function index() : array
    {
        return Freight::select(
            '*', // Select all other columns
            DB::raw('ST_AsText(origin_coord) as origin_coord'),
            DB::raw('ST_AsText(dest_coord) as dest_coord')
        )->get()->toArray();
    }
    /**
     * Create a new freight record.
     *
     * @param FreightRequest $request The validated form request.
     * @param CreateFreightAction $action The action class to handle the business logic.
     * @return JsonResponse
     */
    public function store(FreightRequest $request, CreateFreight $action): JsonResponse
    {
        // Execute the action class with the validated data.

        $response = $action->execute($request->validated());
        return $response;
    }

    /**
     * Update an existing freight record.
     *
     * @param UpdateFreightRequest $request The validated form request.
     * @param UpdateFreightAction $action The action class to handle the business logic.
     * @param string $uuid The UUID of the freight to update.
     * @return JsonResponse
     */
    public function update(UpdateFreightRequest $request, UpdateFreight $action, string $uuid): JsonResponse
    {

        // Execute the action class with the validated data and the UUID.
        $response = $action->execute($uuid, $request->validated());
        return $response;
    }

    /**
     * Delete an existing freight record.
     *
     * @param string $uuid The UUID of the freight to delete.
     * @return JsonResponse
     */
    public function destroy(string $uuid): JsonResponse
    {
        // Implement the delete logic here
        $freight = Freight::where('uuid', $uuid)->first();

        if ($freight) {
            $freight->delete();
            return $this->successResponse(['message' => 'Freight deleted successfully']);
        }

        return $this->errorResponse('Freight not found', 404);
    }

    /**
     * Get a single freight record using its UUID.
     *
     * @param string $uuid The UUID of the freight to retrieve.
     * @return JsonResponse
     */
    public function show(string $uuid): Freight
    {
        // Implement the show logic here
        return Freight::select(
            '*', // Select all other columns
            DB::raw('ST_AsText(origin_coord) as origin_coord'),
            DB::raw('ST_AsText(dest_coord) as dest_coord')
        )->where('uuid', $uuid)->first();
    }


}
