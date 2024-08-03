<?php

namespace App\Http\Requests;

use App\Contracts\Requests\AddPetInterface;
use Illuminate\Foundation\Http\FormRequest;

class PetStoreRequest extends FormRequest implements AddPetInterface
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
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'tags_names' => ['array'],
            'tags_names.*.name' => ['string', 'max:255'],
            'status' => ['required', 'string', 'in:available,pending,sold'],
            'photoUrls' => 'required|array',
            'photoUrls.*' => 'required|url',
        ];
    }

    public function getName(): string
    {
        return $this->string('name');
    }

    public function getStatus(): string
    {
        return $this->string('status');
    }

    public function getTags(): array
    {
        return $this->input('tags_names');
    }

    public function getCategory(): string
    {
        return $this->string('category');
    }

    public function requestAllData(): array
    {
        return [
                'id' => 0,
                'category' => [
                    'id' => 0,
                    'name' => $this->getCategory(),
                ],
                'name' => $this->getName(),
                'photoUrls' => $this->getPhotoUrls(),
                'tags' => array_map(static function ($tagName) {
                    return [
                        'id' => 0,
                        'name' => $tagName,
                    ];
                }, $this->getTags()),
                'status' => $this->getStatus(),
            ];
    }

    public function getPhotoUrls(): array
    {
        return $this->input('photoUrls');
    }

    public function messages()
    {
        return [
            'photoUrls' => 'You need to provide at least one photo url',
            'photoUrls.*' => 'Photo url must be a valid URL',
        ];
    }
}
