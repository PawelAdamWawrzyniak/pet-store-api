<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Contracts\AddPetInterface;
use Illuminate\Support\Facades\Http;
use Psr\Log\LoggerInterface;

readonly class SDKPetStoreAPI
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @param AddPetInterface $request
     * @throw \RuntimeException
     * @return int
     */
    public function addPet(AddPetInterface $request): int
    {
        try {
            $response = Http::post('https://petstore.swagger.io/v2/pet', $request->requestAllData());

            if ($response->status() !== 200) {
                $this->logger->log('error', 'Error while Api was requested' . $response->body());
                throw new \RuntimeException('Error while Api was requested');
            }

            $result = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);

        } catch (\Exception $e) {
            $this->logger->log('error', 'Undefined Error: ' . $e->getMessage());
            throw new \RuntimeException('Undefined error occurs');
        }

        return $result['id'];
    }
}
