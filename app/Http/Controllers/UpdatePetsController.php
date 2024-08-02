<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetGetRequest;
use App\Http\Requests\PetUpdateRequest;
use App\Services\SDKPetStoreAPI;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class UpdatePetsController extends Controller
{
    public function index(SDKPetStoreAPI $SDKPetStoreAPI, PetGetRequest $request): View
    {
        $pet = $SDKPetStoreAPI->getPet($request);

        return view('pets.update.update_form', ['pet' => $pet]);
    }

    public function update(PetUpdateRequest $request, SDKPetStoreAPI $SDKPetStoreAPI): RedirectResponse
    {
        $pet = $SDKPetStoreAPI->updatePet($request);

        $petId = $pet['id'];

        Session::flash('message', sprintf('Pet %s updated successfully', $petId));

        return redirect()->route('pets.detail', ['id' => $petId]);
    }

    public function form(): View
    {
        return view('pets.update_form_by_id');
    }
}
