<?php

namespace Tests\Http\Controllers\Detail;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class GetPetsControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testPetDetailShowed(): void
    {
        //Given
        $data = [
            'id' => 1,
        ];

        //When
        $response = $this->get(route('pets.detail', $data));

        //Then
        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('Pet Details', $response->getContent());
    }

    public function testJsonErrorOccurs(): void
    {
        //Given
        $data = [
            'id' => 1,
        ];

        $this->mockApi(200, 'invalid json', $data['id']);

        // When
        $response = $this->get(route('pets.detail', $data));

        // Then
        $response->assertStatus(400);
        $response->assertSee('Error while parsing json response');
    }

    #[DataProvider('ApiErrorDataProvider')]
    public function testApiErrorResponse(array $data, int $apiResponseStatusCode): void
    {
        $this->mockApi($apiResponseStatusCode, 'no content', $data['id']);

        // When
        $response = $this->get(route('pets.detail', $data));

        // Then
        $response->assertStatus(400);
        $response->assertSee('Error while Api was requested');
    }

    public function mockApi(int $statusCode, string $responseText, int $id): void
    {
        Http::fake([
            'https://petstore.swagger.io/v2/pet/' . $id => Http::response($responseText, $statusCode),
        ]);
    }

    public static function ApiErrorDataProvider(): iterable
    {
        yield 'api returns 400' => [
            'data' => [
                'id' => 1,
            ],
            'apiResponseStatusCode' => 400,
        ];
        yield 'api returns 404' => [
            'data' => [
                'id' => 1,
            ],
            'apiResponseStatusCode' => 404,
        ];
        yield 'api returns 500' => [
            'data' => [
                'id' => 1,
            ],
            'apiResponseStatusCode' => 500,
        ];
    }



}
