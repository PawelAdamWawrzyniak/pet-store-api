<?php

namespace Tests\Http\Controllers\Detail;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GetPetsControllerTest extends TestCase
{
    public function testPetDetailShowed(): void
    {
        //Given
        $data = [
            'id' => 1,
        ];

        $this->mockApi(200, $this->getSampleResponse(), $data['id']);

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

    public function mockApi(int $statusCode, string $responseText, int $id): void
    {
        Http::fake([
            'https://petstore.swagger.io/v2/pet/' . $id => Http::response($responseText, $statusCode),
        ]);
    }

    private function getSampleResponse(): string
    {
        return '{
                    "id": 1,
                "category": {
                        "id": 1,
                    "name": "tak2"
                },
                "name": "Tak2",
                "photoUrls": [
                        "String"
                    ],
                "tags": [
                    {
                        "id": 1,
                        "name": "Tak2"
                    }
                ],
                "status": "Sold"
            }';
    }
}
