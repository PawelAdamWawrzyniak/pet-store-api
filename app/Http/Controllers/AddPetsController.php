<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetStoreRequest;
use App\Http\Services\SDKPetStoreAPI;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class AddPetsController extends Controller
{
    public function index(): View
    {

        return view('pets.add_form');
    }

    public function store(PetStoreRequest $request, SDKPetStoreAPI $SDKPetStoreAPI): RedirectResponse
    {
        try {
            $petId = $SDKPetStoreAPI->addPet($request);
        } catch (\RuntimeException) {
            Session::flash('error', 'Error while Api was requested');
            return redirect()->back();
        }

        Session::flash('message', sprintf('Pet %s added successfully', $petId));

        return redirect()->route('pets.detail', ['id' => $petId]);
    }
}
