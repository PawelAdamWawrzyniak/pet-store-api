<?php

namespace Tests\Integration\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DeletePetsControllerTest extends TestCase
{
    public function testPetDeleted(): void
    {
        //Given
        $data = [
            'id' => 1,
        ];

        $this->mockApi(200, $this->getSampleResponse(), $data['id']);

        //When
        $response = $this->get(route('pets.delete', $data));

        //Then
        $response->assertRedirect();
        $response->assertSessionHas('message',sprintf('Pet %s deleted successfully', $data['id']));
    }

    private function getSampleResponse(): string
    {
        return '{
          "code": 200,
          "type": "unknown",
          "message": "9223372036854656461"
        }';
    }

    public function mockApi(int $statusCode, string $responseText, int $id): void
    {
        Http::fake([
            'https://petstore.swagger.io/v2/pet/' . $id => Http::response($responseText, $statusCode),
        ]);
    }
}
