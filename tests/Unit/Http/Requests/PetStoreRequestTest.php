<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\PetStoreRequest;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PetStoreRequestTest extends TestCase
{
    #[DataProvider('dataProviderForPetStoreRequestValidation')]
    public function testPetStoreRequestValidation(array $data, bool $expectedResult, array $expectedErrors = []): void
    {
        // Given
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
                    'tags_names' => [
                    ],
                    'category' => 'category',
                    'status' => 'available',
                    'photoUrls' => ['https://example.com/image.jpg'],
                ],
                'expectedResult' => true,
            ],
            'missing required fields' => [
                'data' => [],
                'expectedResult' => false,
                'expectedErrors' => ['name', 'category', 'status', 'photoUrls'],
            ],
            'invalid data name' => [
                'data' => [
                    'name' => null,
                    'tags_names' => [
                        [
                            'id' => 0,
                            'name' => 'Tag Name',
                        ]
                    ],
                    'category' => 'category',
                    'status' => 'sold',
                    'photoUrls' => ['https://example.com/image.jpg'],
                ],
                'expectedResult' => false,
                'expectedErrors' => ['name'],
            ],
            'invalid category' => [
                'data' => [
                    'name' => 'Pet Name',
                    'tags_names' => [
                        [
                            'id' => 0,
                            'name' => 'Tag Name',
                        ]
                    ],
                    'category' => 2,
                    'status' => 'available',
                    'photoUrls' => ['https://example.com/image.jpg'],
                ],
                'expectedResult' => false,
                'expectedErrors' => ['category'],
            ],
            'invalid tag_names' => [
                'data' => [
                    'name' => 'Pet Name',
                    'tags_names' => [
                        [
                            'id' => 0,
                            'name' => 2,
                        ]
                    ],
                    'category' => 'category',
                    'status' => 'available',
                    'photoUrls' => ['https://example.com/image.jpg'],
                ],
                'expectedResult' => false,
                'expectedErrors' => ['tags_names.0.name'],
            ],
            'invalid data types' => [
                'data' => [
                    'name' => 123,
                    'tags_names' => [
                        [
                            'id' => 0,
                            'name' => 'Tag Name',
                        ]
                    ],
                    'category' => '',
                    'status' => 'unknown',
                ],
                'expectedResult' => false,
                'expectedErrors' => ['name', 'category', 'status', 'photoUrls'],
            ],
            'invalid status value' => [
                'data' => [
                    'name' => 'Pet Name',
                    'tags_names' => [
                        [
                            'id' => 0,
                            'name' => 'Tag Name',
                        ]
                    ],
                    'category' => 'category',
                    'status' => 'invalid_status',
                ],
                'expectedResult' => false,
                'expectedErrors' => ['status', 'photoUrls'],
            ],
        ];
    }

    public function validateExpectedErrors(array $expectedErrors, Validator $validator): void
    {
        $this->assertSame(array_keys($validator->errors()->toArray()), array_values($expectedErrors));
    }
}
