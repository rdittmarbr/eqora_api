<?php

namespace App\Http\Controllers\Client\V1;

use App\Modules\Client\Exceptions\CityHall\CityHallPropertyNotFoundException;
use App\Modules\Client\Exceptions\CityHall\CityHallRequestException;
use App\Modules\Client\Services\CityHall\CityHallPropertyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PropertyController extends BaseApiController
{
    public function __construct(
        private readonly CityHallPropertyService $cityHallPropertyService
    ) {
    }

    /**
     * Retrieve a property by its municipal registration number.
     */
    public function show(string $registration): JsonResponse
    {
        try {
            $record = $this->cityHallPropertyService->fetchPropertyByRegistration($registration);

            return $this->success(['data' => $record]);
        } catch (CityHallPropertyNotFoundException $exception) {
            Log::warning('Property not found', [
                'registration' => $registration,
                'error' => $exception->getMessage(),
            ]);

            return $this->error('property_not_found', $exception->getMessage(), 404);
        } catch (CityHallRequestException $exception) {
            Log::error('City hall integration error', [
                'registration' => $registration,
                'error' => $exception->getMessage(),
            ]);

            return $this->error('cityhall_unavailable', 'Unable to retrieve property from city hall at the moment.', 503);
        }
    }
}
