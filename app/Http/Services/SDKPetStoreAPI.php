<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Contracts\Requests\AddPetInterface;
use App\Contracts\Requests\FindByStatusPetInterface;
use App\Contracts\Requests\GetPetInterface;
use App\Contracts\Requests\UpdatePetInterface;
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
            $result = $this->handleResponse($response);
        } catch (\Exception $e) {
            $this->logger->log('error', 'Undefined Error: ' . $e->getMessage());
            throw new \RuntimeException('Undefined error occurs');
        }

        return $result['id'];
    }

    /**
     * @param GetPetInterface $request
     * @return array
     * @throw \RuntimeException
     */
    public function getPet(GetPetInterface $request): array
    {
        $response = Http::get('https://petstore.swagger.io/v2/pet/' . $request->getId());
        return $this->handleResponse($response);
    }

    public function list(FindByStatusPetInterface $request): array
    {
        $response = Http::get(
            'https://petstore.swagger.io/v2/pet/findByStatus',
            ['status' => $request->getStatus()]
        );
        return $this->handleResponse($response);
    }

    private function handleResponse($response): array
    {
        if ($response->status() !== 200) {
            $this->logger->log('error', 'Error while Api was requested' . $response->body());
            throw new \RuntimeException('Error while Api was requested', 400);
        }

        try {
            return json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->logger->log('error', 'Error while parsing json response . Error: ' . $e->getMessage());
            throw new \RuntimeException('Error while parsing json response', 400);
        }
    }

    public function updatePet(UpdatePetInterface $request): array
    {
        try {
            $response = Http::put('https://petstore.swagger.io/v2/pet', $request->requestAllData());
            $result = $this->handleResponse($response);
        } catch (\Exception $e) {
            $this->logger->log('error', 'Undefined Error: ' . $e->getMessage());
            throw new \RuntimeException('Undefined error occurs');
        }

        return $result;
    }
}
