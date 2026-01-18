<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Person\StorePersonRequest;
use App\Http\Requests\Api\V1\Person\UpdatePersonRequest;
use App\Http\Resources\Api\V1\PersonResource;
use App\Http\Resources\Api\V1\PersonCollection;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->model = new Person;
        $this->resource = PersonResource::class;
        $this->collection = PersonCollection::class;
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
    public function store(StorePersonRequest $request)
    {
        $validated = $request->validated();
        $person = Person::create($validated);
        
        return new PersonResource($person);
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
    public function update(UpdatePersonRequest $request, $id)
    {
        $person = Person::findOrFail($id);
        $validated = $request->validated();
        $person->update($validated);
        
        return new PersonResource($person);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return parent::destroy($id);
    }
}