<?php

namespace Tests\Http\Requests;

use App\Http\Requests\PetStoreRequest;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PetStoreRequestTest extends TestCase
{
    use DatabaseTransactions;
    #[DataProvider('dataProviderForPetStoreRequestValidation')]
    public function testPetStoreRequestValidation(array $data, bool $expectedResult, array $expectedErrors = []): void
    {
        // Given
        $this->createCategory();
        $this->createTags();
        $request = new PetStoreRequest();

        // When
        $validator = ValidatorFacade::make($data, $request->rules());

        // Then
        $this->assertSame($expectedResult, $validator->passes());
        $this->validateExpectedErrors($expectedErrors, $validator);
    }

    public static function dataProviderForPetStoreRequestValidation(): array
    {
        return [
            'valid data' => [
                'data' => [
                    'name' => 'Pet Name',
                    'category_id' => 1,
                    'tags_ids' => [1, 2],
                    'status' => 'available',
                    'photoUrls' => ['https://example.com/image.jpg'],
                ],
                'expectedResult' => true,
            ],
            'missing required fields' => [
                'data' => [],
                'expectedResult' => false,
                'expectedErrors' => ['name', 'category_id', 'tags_ids', 'status'],
            ],
            'invalid data name' => [
                'data' => [
                    'name' => null,
                    'category_id' => 'one',
                    'tags_ids' => 'not an array',
                    'status' => 'unknown',
                ],
                'expectedResult' => false,
                'expectedErrors' => ['name'],
            ],
            'invalid category' => [
                'data' => [
                    'name' => 'Pet Name',
                    'category_id' => 2,
                    'tags_ids' => [1, 2],
                    'status' => 'available',
                ],
                'expectedResult' => false,
                'expectedErrors' => ['category_id'],
            ],
            'invalid tag_id' => [
                'data' => [
                    'name' => 'Pet Name',
                    'category_id' => 1,
                    'tags_ids' => [3],
                    'status' => 'available',
                ],
                'expectedResult' => false,
                'expectedErrors' => ['tags_ids.0'],
            ],
            'invalid data types' => [
                'data' => [
                    'name' => 123,
                    'category_id' => 'one',
                    'tags_ids' => 'not an array',
                    'status' => 'unknown',
                ],
                'expectedResult' => false,
                'expectedErrors' => ['name', 'category_id', 'tags_ids', 'status'],
            ],
            'invalid status value' => [
                'data' => [
                    'name' => 'Pet Name',
                    'category_id' => 1,
                    'tags_ids' => [1, 2],
                    'status' => 'invalid_status',
                ],
                'expectedResult' => false,
                'expectedErrors' => ['status'],
            ],
        ];
    }

    public function validateExpectedErrors(array $expectedErrors, Validator $validator): void
    {
        foreach ($expectedErrors as $error) {
            $this->assertArrayHasKey($error, $validator->errors()->toArray());
        }
    }

    private function createCategory(): void
    {
        Category::factory()->create([
            'id' => 1,
        ]);
    }

    private function createTags(): void
    {
        Tag::factory()->create([
            'id' => 1,
        ]);
        Tag::factory()->create([
            'id' => 2,
        ]);
    }
}
