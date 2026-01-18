<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Country\StoreCountryRequest;
use App\Http\Requests\Api\V1\Country\UpdateCountryRequest;
use App\Http\Resources\Api\V1\CountryResource;
use App\Http\Resources\Api\V1\CountryCollection;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->model = new Country;
        $this->resource = CountryResource::class;
        $this->collection = CountryCollection::class;
        $this->relations = [];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return parent::index($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCountryRequest $request)
    {
        $validated = $request->validated();
        $country = Country::create($validated);
        
        return new CountryResource($country);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return parent::show($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountryRequest $request, $id)
    {
        $country = Country::findOrFail($id);
        $validated = $request->validated();
        $country->update($validated);
        
        return new CountryResource($country);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return parent::destroy($id);
    }
}