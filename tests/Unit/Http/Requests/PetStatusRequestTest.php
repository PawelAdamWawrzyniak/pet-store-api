<?php

namespace Tests\Unit\Http\Requests;



use App\Http\Requests\PetStatusRequest;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PetStatusRequestTest extends TestCase
{
    #[DataProvider('getPetDetailRequestDataProvider')]
    public function testPetStoreRequestValidation(array $requestData, bool $expectedResult): void
    {
        // Given
        $request = new PetStatusRequest();

        // When
        $validator = Validator::make($requestData, $request->rules());

        // Then
        $this->assertSame($expectedResult, $validator->passes());
    }

    public static function getPetDetailRequestDataProvider(): iterable
    {
        yield 'valid response (available)' => [
            'requestData' => ['status' => 'available'],
            'expectedResult' => true,
        ];

        yield 'valid response (sold)' => [
            'requestData' => ['status' => 'sold'],
            'expectedResult' => true,
        ];

        yield 'valid response (pending)' => [
            'requestData' => ['status' => 'pending'],
            'expectedResult' => true,
        ];

        yield 'invalid status string' => [
            'requestData' => ['status' => '003123413'],
            'expectedResult' => false,
        ];

        yield 'invalid status bool' => [
            'requestData' => ['status' => true],
            'expectedResult' => false,
        ];

        yield 'invalid status int' => [
            'requestData' => ['status' => 4],
            'expectedResult' => false,
        ];
    }
}
