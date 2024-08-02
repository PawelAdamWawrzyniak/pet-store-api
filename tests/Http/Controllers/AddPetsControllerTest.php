<?php

namespace Tests\Http\Controllers;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('ApiErrorDataProvider')]
    public function testApiErrorResponse(array $data, int $apiResponseStatusCode, int $expectedStatusCode): void
    {
        $this->mockApi($apiResponseStatusCode, 'no content');

        // When
        $response = $this->post(route('pets.store'), $data);

        // Then
        $response->assertStatus($expectedStatusCode);
    }

    public function mockApi(int $statusCode, string $responseText): void
    {
        Http::fake([
            'https://petstore.swagger.io/v2/pet' => Http::response($responseText, $statusCode),
        ]);
    }

    public static function ApiErrorDataProvider(): iterable
    {
        yield 'api returns 400' => [
            'data' => [
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
            ],
            'apiResponseStatusCode' => 400,
            'expectedStatusCode' => 400,
        ];
        yield 'api returns 404' => [
            'data' => [
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
            ],
            'apiResponseStatusCode' => 404,
            'expectedStatusCode' => 404,
        ];
        yield 'api returns 500' => [
            'data' => [
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
            ],
            'apiResponseStatusCode' => 500,
            'expectedStatusCode' => 400,
        ];
    }
}
