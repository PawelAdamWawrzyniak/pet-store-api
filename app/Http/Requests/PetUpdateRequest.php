<?php

namespace App\Http\Requests;

use App\Contracts\Requests\UpdatePetInterface;
use Illuminate\Foundation\Http\FormRequest;

class PetUpdateRequest extends FormRequest implements UpdatePetInterface
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
            'tags_names.*.name' => ['string'],
            'status' => ['required', 'string', 'in:available,pending,sold'],
            'photoUrls' => 'array',
            'photoUrls.*' => 'url',
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

    public function getCategory(): ?string
    {
        return $this->input('category');
    }
}
