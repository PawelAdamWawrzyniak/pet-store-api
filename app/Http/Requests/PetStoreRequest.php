<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetStoreRequest extends FormRequest
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
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'tags_ids' => ['required', 'array'],
            'tags_ids.*' => ['integer', 'exists:tags,id'],
            'status' => ['required', 'string', 'in:available,pending,sold'],
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

    public function getTagsIds(): array
    {
        return $this->array('tags_ids');
    }

    public function getCategoryId(): int
    {
        return $this->integer('category_id');
    }
}
