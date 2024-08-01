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
            'name' => 'Test Pet',
            'status' => 'available',
            'tags_ids' => [$tag->id],
            'category_id' => $category->id,
        ];

        //When
        $response = $this->post(route('pets.store'), $data);

        //Then
        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('added successfully', $response->getContent());
    }

    public function testJsonErrorOccurs(): void
    {
        // Given
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();
        $data = [
            'name' => 'Test Pet',
            'status' => 'available',
            'tags_ids' => [$tag->id],
            'category_id' => $category->id,
        ];

        $this->mockApi(200, 'invalid json');

        // When
        $response = $this->post(route('pets.store'), $data);

        // Then
        $response->assertStatus(400);
        $response->assertSee('Error while parsing json response');
    }

    #[DataProvider('ApiErrorDataProvider')]
    public function testApiErrorResponse(array $data, int $apiResponseStatusCode): void
    {
        $category = Category::factory()->create([
            'id' => 1,
        ]);
        $tag = Tag::factory()->create([
            'id' => 1,
        ]);

        $this->mockApi($apiResponseStatusCode, 'no content');

        // When
        $response = $this->post(route('pets.store'), $data);

        // Then
        $response->assertStatus(400);
        $response->assertSee('Error while Api was requested');
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
                'name' => 'Test Pet',
                'status' => 'available',
                'tags_ids' => [1],
                'category_id' => 1,
            ],
            'apiResponseStatusCode' => 400,
        ];
        yield 'api returns 404' => [
            'data' => [
                'name' => 'Test Pet',
                'status' => 'available',
                'tags_ids' => [1],
                'category_id' => 1,
            ],
            'apiResponseStatusCode' => 404,
        ];
        yield 'api returns 500' => [
            'data' => [
                'name' => 'Test Pet',
                'status' => 'available',
                'tags_ids' => [1],
                'category_id' => 1,
            ],
            'apiResponseStatusCode' => 500,
        ];
    }
}
