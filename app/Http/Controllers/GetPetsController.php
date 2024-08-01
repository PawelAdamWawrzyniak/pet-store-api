<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetGetRequest;
use App\Http\Requests\PetStatusRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Psr\Log\LoggerInterface;

class GetPetsController extends Controller
{
    public function index(): View
    {
        return view('pets.get_form');
    }

    public function detail(PetGetRequest $request, LoggerInterface $logger): Response
    {
        try {
            $response = Http::get('https://petstore.swagger.io/v2/pet/' . $request->getId());

            if ($response->status() !== 200) {
                return new Response('Error while Api was requested', 400);
            }

            $pet = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);

        } catch (\JsonException $e) {
            $logger->log('error', 'Error while parsing json response . Error: ' . $e->getMessage());
            return new Response('Error while parsing json response', 400);
        }

        return new Response(view('pets.detail', ['pet' => $pet]), 200);
    }

    public function status(): View
    {
        return view('pets.get_status_form');
    }

    public function list(PetStatusRequest $request, LoggerInterface $logger): Response
    {
        try {
            $response = Http::get('https://petstore.swagger.io/v2/pet/findByStatus',['status' => $request->getStatus()]);

            if ($response->status() !== 200) {
                return new Response('Error while Api was requested', 400);
            }

            $pets = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);

        } catch (\JsonException $e) {
            $logger->log('error', 'Error while parsing json response . Error: ' . $e->getMessage());
            return new Response('Error while parsing json response', 400);
        }

        return new Response(view('pets.list', ['pets' => $pets]), 200);
    }
}
