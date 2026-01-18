<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\City\StoreCityRequest;
use App\Http\Requests\Api\V1\City\UpdateCityRequest;
use App\Http\Resources\Api\V1\CityResource;
use App\Http\Resources\Api\V1\CityCollection;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->model = new City;
        $this->resource = CityResource::class;
        $this->collection = CityCollection::class;
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
    public function store(StoreCityRequest $request)
    {
        $validated = $request->validated();
        $city = City::create($validated);
        
        return new CityResource($city);
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
    public function update(UpdateCityRequest $request, $id)
    {
        $city = City::findOrFail($id);
        $validated = $request->validated();
        $city->update($validated);
        
        return new CityResource($city);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return parent::destroy($id);
    }
}