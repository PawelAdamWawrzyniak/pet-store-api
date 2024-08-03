<?php

namespace Tests\Services;


use App\Contracts\Requests\AddPetInterface;
use App\Contracts\Requests\GetPetInterface;
use App\Contracts\Requests\UpdatePetInterface;
use App\Http\Requests\PetStoreRequest;
use App\Services\SDKPetStoreAPI;
use Exception;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class SDKPetStoreAPITest extends TestCase
{

    #[DataProvider('PetHandleStatusCodesDataProvider')]
    public function testAddPetHandleResponses(string $expectedMessage, int $responseCode): void
    {
        // Given
        $SDKPetStoreAPI = new SDKPetStoreAPI($this->createMock(LoggerInterface::class));
        $requestMock = $this->mockAddRequest();
        $this->mockApi(
            'https://petstore.swagger.io/v2/pet',
            $responseCode,
            'Bad Request - Error while Api was requested'
        );

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage($expectedMessage);

        // When
        $SDKPetStoreAPI->addPet($requestMock);
    }

    #[DataProvider('PetHandleStatusCodesDataProvider')]
    public function testGetPetHandleResponses(string $expectedMessage, int $responseCode): void
    {
        // Given
        $petId = 1;
        $SDKPetStoreAPI = new SDKPetStoreAPI($this->createMock(LoggerInterface::class));
        $requestMock = $this->mockGetRequest($petId);
        $this->mockApi(
            'https://petstore.swagger.io/v2/pet/' . $petId,
            $responseCode,
            'Bad Request - Error while Api was requested'
        );

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage($expectedMessage);

        // When
        $SDKPetStoreAPI->getPet($requestMock);
    }

    #[DataProvider('PetHandleStatusCodesDataProvider')]
    public function testUpdatePetHandleResponses(string $expectedMessage, int $responseCode): void
    {
        // Given
        $petId = 1;
        $SDKPetStoreAPI = new SDKPetStoreAPI($this->createMock(LoggerInterface::class));
        $requestMock = $this->mockUpdateRequest($petId);
        $this->mockApi(
            'https://petstore.swagger.io/v2/pet',
            $responseCode,
            'Bad Request - Error while Api was requested'
        );

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage($expectedMessage);

        // When
        $SDKPetStoreAPI->updatePet($requestMock);
    }

    #[DataProvider('PetHandleStatusCodesDataProvider')]
    public function testDeletePetHandleResponses(string $expectedMessage, int $responseCode): void
    {
        // Given
        $petId = 1;
        $SDKPetStoreAPI = new SDKPetStoreAPI($this->createMock(LoggerInterface::class));
        $requestMock = $this->mockGetRequest($petId);
        $this->mockApi(
            'https://petstore.swagger.io/v2/pet/' . $petId,
            $responseCode,
            'Bad Request - Error while Api was requested'
        );

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage($expectedMessage);

        // When
        $SDKPetStoreAPI->deletePet($requestMock);
    }

    public static function petHandleStatusCodesDataProvider(): iterable
    {
        yield '400 Bad Request' => [
            'expectedMessage' => 'Error while Api was requested',
            'responseCode' => 400,
        ];

        yield '418 Bad Request' => [
            'expectedMessage' => 'Error while Api was requested',
            'responseCode' => 418,
        ];

        yield '404 Bad Request' => [
            'expectedMessage' => 'Resource Not Found - Error 404 while Api was requested',
            'responseCode' => 404,
        ];

        yield '405 Bad Request' => [
            'expectedMessage' => 'Invalid Input in Request - Error while Api was requested',
            'responseCode' => 405,
        ];

        yield '500 Bad Request' => [
            'expectedMessage' => 'Error while Api was requested',
            'responseCode' => 500,
        ];
    }

    public function mockApi(string $url, int $statusCode, string $responseText): void
    {
        Http::fake([
            $url => Http::response($responseText, $statusCode),
        ]);
    }

    private function mockAddRequest(): AddPetInterface|MockInterface
    {
        $requestMock = $this->mock(AddPetInterface::class);
        $requestMock->allows([
            'getId' => 1,
            'requestAllData' => [
                'id' => 1,
                'name' => 'Test Pet',
                'status' => 'available',
                'tags_names' => [
                    [
                        'id' => 0,
                        'name' => 'Tag Name',
                    ]
                ],
                'category' => 'category',
                'photoUrls' => ['https://example.com/image.jpg'],
            ]
        ]);
        return $requestMock;
    }

    private function mockGetRequest(int $id): GetPetInterface|MockInterface
    {
        $requestMock = $this->mock(GetPetInterface::class);
        $requestMock->allows([
            'getId' => $id,
        ]);
        return $requestMock;
    }

    private function mockUpdateRequest(int $petId): UpdatePetInterface|MockInterface
    {
        $requestMock = $this->mock(UpdatePetInterface::class);
        $requestMock->allows([
            'getId' => $petId,
            'requestAllData' => [
                'id' => $petId,
                'name' => 'Test Pet',
                'status' => 'available',
                'tags_names' => [
                    [
                        'id' => 0,
                        'name' => 'Tag Name',
                    ]
                ],
                'category' => 'category',
                'photoUrls' => ['https://example.com/image.jpg'],
            ]
        ]);
        return $requestMock;
    }
}
