<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetGetRequest;
use App\Http\Requests\PetUpdateRequest;
use App\Http\Services\SDKPetStoreAPI;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class DeletePetsController extends Controller
{
    public function delete(SDKPetStoreAPI $SDKPetStoreAPI, PetGetRequest $request): RedirectResponse
    {
        $petId = $SDKPetStoreAPI->deletePet($request);

        Session::flash('message', sprintf('Pet %s deleted successfully', $petId));

        return redirect()->back();
    }

    public function form(): View
    {
        return view('pets.delete.delete_form_by_id');
    }
}
