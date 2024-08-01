<?php

namespace Tests\Http\Controllers\FindByStatus;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class GetPetsControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testPetListShowed(): void
    {
        //Given
        $data = [
            'status' => 'available',
        ];

        //When
        $response = $this->get(route('pets.status.list', $data));

        //Then
        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('Pet List', $response->getContent());
    }

    public function testJsonErrorOccurs(): void
    {
        //Given
        $data = [
            'status' => 'available',
        ];

        $this->mockApi(200, 'invalid json', $data['status']);

        // When
        $response = $this->get(route('pets.status.list', $data));

        // Then
        $response->assertStatus(400);
        $response->assertSee('Error while parsing json response');
    }

    #[DataProvider('ApiErrorDataProvider')]
    public function testApiErrorResponse(array $data, int $apiResponseStatusCode): void
    {
        $this->mockApi($apiResponseStatusCode, 'no content', $data['status']);

        // When
        $response = $this->get(route('pets.status.list', $data));

        // Then
        $response->assertStatus(400);
        $response->assertSee('Error while Api was requested');
    }

    public function mockApi(int $statusCode, string $responseText, string $status): void
    {
        Http::fake([
            'https://petstore.swagger.io/v2/pet/findByStatus?status=' . $status => Http::response($responseText, $statusCode),
        ]);
    }

    public static function ApiErrorDataProvider(): iterable
    {
        yield 'api returns 400' => [
            'data' => [
                'status' => 'available',
            ],
            'apiResponseStatusCode' => 400,
        ];
        yield 'api returns 404' => [
            'data' => [
                'status' => 'sold',
            ],
            'apiResponseStatusCode' => 404,
        ];
        yield 'api returns 500' => [
            'data' => [
                'status' => 'pending',
            ],
            'apiResponseStatusCode' => 500,
        ];
    }



}
