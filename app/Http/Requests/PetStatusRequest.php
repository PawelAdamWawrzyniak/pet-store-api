<?php

namespace App\Http\Requests;

use App\Contracts\Requests\FindByStatusPetInterface;
use Illuminate\Foundation\Http\FormRequest;

class PetStatusRequest extends FormRequest implements FindByStatusPetInterface
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
            'status' => ['required', 'string', 'in:available,pending,sold'],
        ];
    }

    public function getStatus(): string
    {
        return $this->input('status');
    }
}
