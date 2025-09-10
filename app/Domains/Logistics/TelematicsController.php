<?php

namespace App\Domains\Logistics;

use App\Domains\Logistics\Services\TelematicsService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class TelematicsController extends BaseController
{
    public function __construct(private readonly TelematicsService $telematicsService)
    {
    }

    public function index(Request $request, int $vehicleId)
    {
        $perPage = (int) ($request->query('per_page', 15));
        return $this->telematicsService->listForVehicle($vehicleId, $perPage);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => ['required', 'integer'],
            'location' => ['nullable', 'string'],
            'speed' => ['nullable', 'numeric'],
            'engine_status' => ['nullable', 'string'],
            'recorded_at' => ['required', 'date'],
        ]);

        $telematics = $this->telematicsService->create($validated);
        return response()->json($telematics, Response::HTTP_CREATED);
    }

    public function show(int $id)
    {
        $telematics = $this->telematicsService->find($id);
        abort_if(!$telematics, 404);
        return $telematics;
    }

    public function update(Request $request, int $id)
    {
        $telematics = $this->telematicsService->find($id);
        abort_if(!$telematics, 404);

        $validated = $request->validate([
            'location' => ['nullable', 'string'],
            'speed' => ['nullable', 'numeric'],
            'engine_status' => ['nullable', 'string'],
            'recorded_at' => ['nullable', 'date'],
        ]);

        $updated = $this->telematicsService->update($telematics, $validated);
        return response()->json($updated);
    }

    public function destroy(int $id)
    {
        $telematics = $this->telematicsService->find($id);
        abort_if(!$telematics, 404);
        $this->telematicsService->delete($telematics);
        return response()->noContent();
    }
}


