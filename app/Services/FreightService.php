<?php

namespace App\Services;

use App\Models\Freight;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;

class FreightService
{
    use ApiResponse;

    /**
     * Create a new freight record.
     *
     * @param array $data The data for the new freight entry.
     * @return JsonResponse
     */
    public function create(array $data): JsonResponse
    {
        // Get the authenticated user's ID
        $loadOwnerId = Auth::id();

        Log::info('Creating freight for user: ' . $data['origin_location']);

        if (!$loadOwnerId) {
            return $this->errorResponse('User not authenticated.', 401);
        }

        DB::beginTransaction();

        try {
            // Check if coordinates exist and format them for the database
            if (isset($data['origin_coord'])) {
                $originCoords = explode(',', $data['origin_coord']);
                $data['origin_coord'] = DB::raw("ST_PointFromText('POINT(" . trim($originCoords[0]) . " " . trim($originCoords[1]) . ")')");
            }
            if (isset($data['dest_coord'])) {
                $destCoords = explode(',', $data['dest_coord']);
                $data['dest_coord'] = DB::raw("ST_PointFromText('POINT(" . trim($destCoords[0]) . " " . trim($destCoords[1]) . ")')");
            }

            $data['load_owner_id'] = $loadOwnerId;

            // `required_equipment` is cast to array in model, so pass it directly if it's already an array,
            // or as a string that the cast will convert to array.
            if (isset($data['required_equipment']) && is_string($data['required_equipment'])) {
                 // Convert a single string into an array for the model's cast
                 $data['required_equipment'] = [$data['required_equipment']];
            }

            $freight = Freight::create($data);

            DB::commit();

            return $this->successResponse(["freight" => $freight], 'Freight created successfully.', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create freight: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update an existing freight record using its UUID.
     *
     * @param string $uuid The UUID of the freight to update.
     * @param array $data The data to update the freight with.
     * @return JsonResponse
     */
    

    /**
     * Delete a freight record using its UUID.
     *
     * @param string $uuid The UUID of the freight to delete.
     * @return JsonResponse
     */
    public function deleteFreight(string $uuid): JsonResponse
    {
        $freight = Freight::where('uuid', $uuid)->first();

        if ($freight) {
            $freight->delete();
            return $this->successResponse(['message' => 'Freight deleted successfully']);
        }

        return $this->errorResponse('Freight not found', 404);
    }

    /**
     * Get a single freight record using its UUID, converting coordinates to WKT.
     *
     * @param string $uuid The UUID of the freight to retrieve.
     * @return Freight|null
     */
    public function getFreight(string $uuid): ?Freight
    {
        return Freight::select(
            '*', // Select all other columns
            DB::raw('ST_AsText(origin_coord) as origin_coord'),
            DB::raw('ST_AsText(dest_coord) as dest_coord')
        )->where('uuid', $uuid)->first();
    }

    /**
     * Get a single freight record by UUID (helper for controller authorization), converting coordinates to WKT.
     *
     * @param string $uuid
     * @return Freight|null
     */
    public function getFreightByUuid(string $uuid): ?Freight
    {
        // This method is for internal use where the raw model might be needed,
        // but for returning to JSON, the `getFreight` method should be preferred.
        // For authorization, fetching just the ID and load_owner_id might be more efficient.
        return Freight::select('id', 'uuid', 'load_owner_id', DB::raw('ST_AsText(origin_coord) as origin_coord'), DB::raw('ST_AsText(dest_coord) as dest_coord'))
                      ->where('uuid', $uuid)
                      ->first();
    }

    /**
     * Get all freight records, converting coordinates to WKT.
     *
     * @return array
     */
    public function getAllFreights(): array
    {
        return Freight::select(
            '*', // Select all other columns
            DB::raw('ST_AsText(origin_coord) as origin_coord'),
            DB::raw('ST_AsText(dest_coord) as dest_coord')
        )->get()->toArray();
    }

    /**
     * Search for freights by description, converting coordinates to WKT.
     *
     * @param string $query The search query.
     * @return array
     */
    public function searchFreights(string $query): array
    {
        return Freight::select(
            '*', // Select all other columns
            DB::raw('ST_AsText(origin_coord) as origin_coord'),
            DB::raw('ST_AsText(dest_coord) as dest_coord')
        )->where('description', 'like', "%{$query}%")->get()->toArray();
    }
}
