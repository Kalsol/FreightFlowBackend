<?php

namespace App\Domains\Freight\Actions;

use App\Models\Freight;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UpdateFreight
{
    use ApiResponse;
    /**
     * Update an existing freight record in the database.
     *
     * @param string $uuid The UUID of the freight to update.
     * @param array $data The validated data from the request.
     * @return Freight
     * @throws \Exception If the freight is not found or the transaction fails.
     */
    public function execute(string $uuid, array $data): JsonResponse
    {
        $loadOwnerId = Auth::id();

        if (!$loadOwnerId) {
            return $this->errorResponse('User not authenticated.', 401);
        }
        
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Find the freight record by its UUID. firstOrFail() will throw an exception if not found.
            $freight = Freight::where('uuid', $uuid)->firstOrFail();

            // Check if the freight belongs to the authenticated user
            if ($freight->load_owner_id !== $loadOwnerId) {
                return $this->errorResponse('You are not authorized to update this freight.', 403);
            }

            // Handle the geometry fields
            if (isset($data['origin_coord'])) {
                $originCoords = explode(',', $data['origin_coord']);
                $data['origin_coord'] = DB::raw("ST_PointFromText('POINT(" . trim($originCoords[0]) . " " . trim($originCoords[1]) . ")')");
            }
            if (isset($data['dest_coord'])) {
                $destCoords = explode(',', $data['dest_coord']);
                $data['dest_coord'] = DB::raw("ST_PointFromText('POINT(" . trim($destCoords[0]) . " " . trim($destCoords[1]) . ")')");
            }

            // Convert `bid_deadline` to a Carbon instance for proper formatting
            if (isset($data['bid_deadline'])) {
                $data['bid_deadline'] = Carbon::parse($data['bid_deadline']);
            }

            // Convert `required_equipment` to a JSON string for direct insertion if it's an array
            if (isset($data['required_equipment']) && is_array($data['required_equipment'])) {
                $data['required_equipment'] = json_encode($data['required_equipment']);
            }

            // Perform the update
            $freight->update($data);

            DB::commit();

            return $this->successResponse([$freight],'Freight updated successfully.', 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            // Re-throw a new exception for consistency, allowing the controller to handle the response
            throw new Exception('Failed to update freight: ' . $e->getMessage(), 0, $e);
        }
    }
}
