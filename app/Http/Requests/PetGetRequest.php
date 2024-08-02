<?php

namespace App\Http\Requests;

use App\Contracts\Requests\GetPetInterface;
use Illuminate\Foundation\Http\FormRequest;

class PetGetRequest extends FormRequest implements GetPetInterface
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer',
        ];
    }

    public function getId(): int
    {
        return $this->integer('id');
    }
}
