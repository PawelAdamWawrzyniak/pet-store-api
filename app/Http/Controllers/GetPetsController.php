<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetGetRequest;
use App\Http\Requests\PetStatusRequest;
use App\Http\Services\SDKPetStoreAPI;
use Illuminate\Http\Response;
use Illuminate\View\View;

class GetPetsController extends Controller
{
    public function __construct(private readonly SDKPetStoreAPI $SDKPetStoreAPI)
    {
    }

    public function index(): View
    {
        return view('pets.get_form');
    }

    public function detail(PetGetRequest $request): Response
    {
        try {
            $pet = $this->SDKPetStoreAPI->getPet($request);
        } catch (\RuntimeException) {
            return new Response('Error while Api was requested', 400);
        }

        return new Response(view('pets.detail', ['pet' => $pet]), 200);
    }

    public function status(): View
    {
        return view('pets.get_status_form');
    }

    public function list(PetStatusRequest $request): Response
    {
        try {
            $pets = $this->SDKPetStoreAPI->list($request);
        } catch (\RuntimeException) {
            return new Response('Error while Api was requested', 400);
        }

        return new Response(view('pets.list', ['pets' => $pets]), 200);
    }
}
