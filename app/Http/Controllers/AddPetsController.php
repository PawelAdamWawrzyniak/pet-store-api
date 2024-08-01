<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetStoreRequest;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class AddPetsController extends Controller
{
    public function index(): View
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('pets.add_form', ['categories' => $categories, 'tags' => $tags]);
    }

    public function store(PetStoreRequest $request): Response
    {
        try {
            $validatedData = $request->validated();

            $response = Http::post('https://petstore.swagger.io/v2/pet', [
                'id' => 0,
                'category' => [
                    'id' => $validatedData['category_id'],
                    'name' => Category::find((int)$validatedData['category_id'])->name,
                ],
                'name' => $validatedData['name'],
                'photoUrls' => [],
                'tags' => array_map(function ($tagId) {
                    return [
                        'id' => $tagId,
                        'name' => Tag::find((int)$tagId)->name,
                    ];
                }, $validatedData['tags_ids']),
                'status' => $validatedData['status'],
            ]);

            if ($response->status() !== 200) {
                return new Response('Error while Api was requested', 400);
            }

            $result = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return new Response('Error while parsing json response', 400);
        }

        return new Response('Pet ' . $result['id'] . ' added successfully', 200);
    }
}
