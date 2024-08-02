<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetStoreRequest;
use App\Services\SDKPetStoreAPI;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class AddPetsController extends Controller
{
    public function index(): View
    {
        return view('pets.add.add_form');
    }

    public function add(PetStoreRequest $request, SDKPetStoreAPI $SDKPetStoreAPI): RedirectResponse
    {
        $petId = $SDKPetStoreAPI->addPet($request);

        Session::flash('message', sprintf('Pet %s added successfully', $petId));

        return redirect()->route('pets.detail', ['id' => $petId]);
    }
}
