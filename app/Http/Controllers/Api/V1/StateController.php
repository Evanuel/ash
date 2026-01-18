<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\State\StoreStateRequest;
use App\Http\Requests\Api\V1\State\UpdateStateRequest;
use App\Http\Resources\Api\V1\StateResource;
use App\Http\Resources\Api\V1\StateCollection;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->model = new State;
        $this->resource = StateResource::class;
        $this->collection = StateCollection::class;
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
    public function store(StoreStateRequest $request)
    {
        $validated = $request->validated();
        $state = State::create($validated);
        
        return new StateResource($state);
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
    public function update(UpdateStateRequest $request, $id)
    {
        $state = State::findOrFail($id);
        $validated = $request->validated();
        $state->update($validated);
        
        return new StateResource($state);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return parent::destroy($id);
    }
}