<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Requests\AddPetInterface;
use App\Contracts\Requests\FindByStatusPetInterface;
use App\Contracts\Requests\GetPetInterface;
use App\Contracts\Requests\UpdatePetInterface;
use App\Http\Requests\PetGetRequest;
use Illuminate\Support\Facades\Http;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $response = Http::post('https://petstore.swagger.io/v2/pet', $request->requestAllData());
        $result = $this->handleResponse($response);

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
        if ($response->status() === 400) {
            throw new HttpException(400, 'Bad Request - Error while Api was requested',);
        }

        if ($response->status() === 404) {
            throw new NotFoundHttpException('Resource Not Found - Error 404 while Api was requested', null, 404);
        }

        if ($response->status() === 405) {
            throw new HttpException(405, 'Invalid Input in Request - Error while Api was requested',);
        }

        if ($response->status() !== 200) {
            $this->logger->log('error', 'Error while Api was requested' . $response->body());
            throw new HttpException(400,'Error while Api was requested');
        }

        try {
            return json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->logger->log('error', 'Error while parsing json response . Error: ' . $e->getMessage());
            throw new HttpException(400, 'Error while parsing json response');
        }
    }

    public function updatePet(UpdatePetInterface $request): array
    {
        $response = Http::put('https://petstore.swagger.io/v2/pet', $request->requestAllData());
        return $this->handleResponse($response);
    }

    public function deletePet(GetPetInterface $request): int
    {
        $response = Http::delete('https://petstore.swagger.io/v2/pet/' . $request->getId());
        $this->handleResponse($response);

        return $request->getId();
    }
}
