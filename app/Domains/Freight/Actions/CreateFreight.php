<?php

namespace App\Domains\Freight\Actions;

use App\Domains\Freight\Freight;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CreateFreight
{
    use ApiResponse;

    /**
     * Create a new freight record in the database.
     *
     * @param array $data The validated data from the request.
     * @return JsonResponse
     * @throws \Exception If the database transaction fails.
     */
    public function execute(array $data): JsonResponse
    {
        $loadOwnerId = Auth::id();

        if (!$loadOwnerId) {
            return $this->errorResponse('User not authenticated.', 401);
        }

        DB::beginTransaction();

        try {
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

            // Ensure timestamps are included for direct database insertion
            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();

            // Convert `required_equipment` to a JSON string for direct insertion if it's an array
            if (isset($data['required_equipment']) && is_array($data['required_equipment'])) {
                $data['required_equipment'] = json_encode($data['required_equipment']);
            }
            
            $data['load_owner_id'] = $loadOwnerId;
            $data['uuid'] = Str::uuid();
            
            // Use DB::table()->insert() for a clean insert that handles DB::raw
            $success = DB::table('freights')->insert($data);

            if (!$success) {
                DB::rollBack();
                return $this->errorResponse('Failed to insert freight data into the database.', 500);
            }

            DB::commit();

            // Retrieve the newly created freight record with the coordinate conversion applied
            $freight = Freight::select(
                '*',
                DB::raw('ST_AsText(origin_coord) as origin_coord'),
                DB::raw('ST_AsText(dest_coord) as dest_coord')
            )->where('uuid', $data['uuid'])->first();

            return $this->successResponse(['freight' => $freight], 'Freight created successfully.', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create freight: ' . $e->getMessage(), 500);
        }
    }
}
