<?php

namespace App\Modules\Client\Services\CityHall;

use App\Modules\Client\Exceptions\CityHall\CityHallPropertyNotFoundException;
use App\Modules\Client\Exceptions\CityHall\CityHallRequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class CityHallPropertyService
{
    public function fetchPropertyByRegistration(string $registration): array
    {
        $config = config('services.cityhall');
        if (empty($config['base_url']) || empty($config['token'])) {
            throw CityHallRequestException::communicationError('City hall service is not configured properly.');
        }
        $endpoint = rtrim($config['base_url'], '/') . '/properties/' . urlencode($registration);
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $config['token'],
        ];

        try {
            $response = Http::withHeaders($headers)
                ->timeout((int) $config['timeout'])
                ->retry(
                    (int) ($config['retry']['times'] ?? 2),
                    (int) ($config['retry']['sleep'] ?? 200)
                )
                ->get($endpoint);

            Log::channel('cityhall')->info('City hall request', [
                'endpoint' => $endpoint,
                'registration' => $registration,
                'status' => $response->status(),
            ]);

            if ($response->status() === 404) {
                throw CityHallPropertyNotFoundException::forRegistration($registration);
            }

            if (!$response->successful()) {
                throw CityHallRequestException::failed($response->status());
            }

            $payload = $response->json();

            if (empty($payload['data'])) {
                throw CityHallPropertyNotFoundException::forRegistration($registration);
            }

            return $payload['data'];
        } catch (CityHallPropertyNotFoundException|CityHallRequestException $exception) {
            Log::channel('cityhall')->warning('City hall known error', [
                'registration' => $registration,
                'error' => $exception->getMessage(),
            ]);
            throw $exception;
        } catch (ConnectionException|RequestException $exception) {
            Log::channel('cityhall')->error('City hall communication issue', [
                'registration' => $registration,
                'error' => $exception->getMessage(),
            ]);
            throw CityHallRequestException::communicationError(
                'Unable to communicate with city hall service.',
                $exception
            );
        } catch (Throwable $exception) {
            Log::channel('cityhall')->critical('City hall unexpected error', [
                'registration' => $registration,
                'error' => $exception->getMessage(),
            ]);
            throw CityHallRequestException::communicationError(
                'Unexpected error while fetching property.',
                $exception
            );
        }
    }
}
