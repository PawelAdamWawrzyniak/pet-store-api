<?php

namespace Tests\Integration\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AddPetsControllerTest extends TestCase
{
    public function testNewPetsIsAdded(): void
    {
        //Given
        $data = [
            'id' => 10,
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
        ];
        $this->mockApi(200, json_encode($data));

        //When
        $response = $this->post(route('pets.store'), $data);

        //Then
        $response->assertRedirect(route('pets.detail', ['id' => 10]));
        $response->assertSessionHas('message', 'Pet 10 added successfully');
    }

    public function testJsonErrorOccurs(): void
    {
        // Given
        $data = [
            'id' => 10,
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
        ];

        $this->mockApi(200, 'invalid json');

        // When
        $response = $this->post(route('pets.store'), $data);

        // Then
        $response->assertStatus(400);
        $response->assertSee('Error while parsing json response');
    }

    public function mockApi(int $statusCode, string $responseText): void
    {
        Http::fake([
            'https://petstore.swagger.io/v2/pet' => Http::response($responseText, $statusCode),
        ]);
    }
}
