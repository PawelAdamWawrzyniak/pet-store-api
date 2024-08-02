<?php

namespace Tests\Http\Controllers;


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UpdatePetsControllerTest extends TestCase
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
                   'name' => 'Tag Name',
                ]
            ],
            'category' => 'category',
            'photoUrls' => ['https://example.com/image.jpg'],
        ];
        $this->mockApi(200, json_encode($data));

        //When
        $response = $this->put(route('pets.update'), $data);

        //Then
        $response->assertRedirect(route('pets.detail', ['id' => 10]));
        $response->assertSessionHas('message', 'Pet 10 updated successfully');
    }

    public function mockApi(int $statusCode, string $responseText): void
    {
        Http::fake([
            'https://petstore.swagger.io/v2/pet' => Http::response($responseText, $statusCode),
        ]);
    }
}
