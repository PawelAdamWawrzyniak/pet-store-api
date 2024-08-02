<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetStoreRequest;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Psr\Log\LoggerInterface;

class AddPetsController extends Controller
{
    public function index(): View
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('pets.add_form', ['categories' => $categories, 'tags' => $tags]);
    }

    public function store(PetStoreRequest $request, LoggerInterface $logger): RedirectResponse
    {
        try {

            $response = Http::post('https://petstore.swagger.io/v2/pet', $request->requestAllData());

            if ($response->status() !== 200) {
                $logger->log('error', 'Error while Api was requested'. $response->body());
                throw new \RuntimeException('Error while Api was requested');
            }

            $result = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
        }
        catch (\RuntimeException) {
            Session::flash('error', 'Error while Api was requested');
            return redirect()->back();
        }
        catch (\Exception $e) {
            $logger->log('error', 'Undefined Error: ' . $e->getMessage());
            Session::flash('error', 'Undefined error occurs');
            return redirect()->back();
        }

        $petId = $result['id'];

        Session::flash('message', sprintf('Pet %s added successfully', $petId));

        return redirect()->route('pets.detail', ['id' => $petId]);
    }
}
