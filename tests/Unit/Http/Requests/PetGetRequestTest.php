<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\PetGetRequest;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PetGetRequestTest extends TestCase
{
    #[DataProvider('getPetDetailRequestDataProvider')]
    public function testPetStoreRequestValidation(array $requestData, bool $expectedResult): void
    {
        // Given
        $request = new PetGetRequest();

        // When
        $validator = Validator::make($requestData, $request->rules());

        // Then
        $this->assertSame($expectedResult, $validator->passes());
    }

    public static function getPetDetailRequestDataProvider(): iterable
    {
        yield 'valid response' => [
            'requestData' => ['id' => 3123413],
            'expectedResult' => true,
        ];

        yield 'id is string' => [
            'requestData' => ['id' => '003123413'],
            'expectedResult' => false,
        ];

        yield 'invalid response' => [
            'requestData' => ['id' => 'two'],
            'expectedResult' => false,
        ];
    }
}
