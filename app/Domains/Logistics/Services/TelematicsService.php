<?php

namespace App\Domains\Logistics\Services;

use App\Domains\Logistics\Models\Telematics;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TelematicsService
{
    public function listForVehicle(int $vehicleId, int $perPage = 15): LengthAwarePaginator
    {
        return Telematics::query()
            ->where('vehicle_id', $vehicleId)
            ->orderByDesc('recorded_at')
            ->paginate($perPage);
    }

    public function create(array $attributes): Telematics
    {
        return Telematics::query()->create($attributes);
    }

    public function find(int $id): ?Telematics
    {
        return Telematics::query()->find($id);
    }

    public function update(Telematics $telematics, array $attributes): Telematics
    {
        $telematics->fill($attributes);
        $telematics->save();
        return $telematics;
    }

    public function delete(Telematics $telematics): void
    {
        $telematics->delete();
    }
}


