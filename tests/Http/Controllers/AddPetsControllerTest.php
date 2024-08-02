<?php

namespace Tests\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AddPetsControllerTest extends TestCase
{
    use DatabaseTransactions;
    public function testNewPetsIsAdded(): void
    {
        //Given
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();
        $data = [
            'id' => 10,
            'name' => 'Test Pet',
            'status' => 'available',
            'tags_ids' => [$tag->id],
            'category_id' => $category->id,
            'photoUrls' => ['https://example.com/image.jpg'],
        ];
        $this->mockApi(200, json_encode($data));

        //When
        $response = $this->post(route('pets.store'), $data);

        //Then
        $response->assertRedirect(route('pets.detail',['id' => 10]));
        $response->assertSessionHas('message', 'Pet 10 added successfully');
    }

    public function testJsonErrorOccurs(): void
    {
        // Given
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();
        $data = [
            'id' => 10,
            'name' => 'Test Pet',
            'status' => 'available',
            'tags_ids' => [$tag->id],
            'category_id' => $category->id,
            'photoUrls' => ['https://example.com/image.jpg'],
        ];

        $this->mockApi(200, 'invalid json');

        // When
        $response = $this->post(route('pets.store'), $data);

        // Then
        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Error while Api was requested');
    }

    #[DataProvider('ApiErrorDataProvider')]
    public function testApiErrorResponse(array $data, int $apiResponseStatusCode): void
    {
        Category::factory()->create([
            'id' => 1,
        ]);
        Tag::factory()->create([
            'id' => 1,
        ]);
        $this->mockApi($apiResponseStatusCode, 'no content');

        // When
        $response = $this->post(route('pets.store'), $data);

        // Then
        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Error while Api was requested');
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
                'tags_ids' => [1],
                'category_id' => 1,
                'photoUrls' => ['https://example.com/image.jpg'],
            ],
            'apiResponseStatusCode' => 400,
        ];
        yield 'api returns 404' => [
            'data' => [
                'id' => 10,
                'name' => 'Test Pet',
                'status' => 'available',
                'tags_ids' => [1],
                'category_id' => 1,
                'photoUrls' => ['https://example.com/image.jpg'],
            ],
            'apiResponseStatusCode' => 404,
        ];
        yield 'api returns 500' => [
            'data' => [
                'id' => 10,
                'name' => 'Test Pet',
                'status' => 'available',
                'tags_ids' => [1],
                'category_id' => 1,
                'photoUrls' => ['https://example.com/image.jpg'],
            ],
            'apiResponseStatusCode' => 500,
        ];
    }
}
