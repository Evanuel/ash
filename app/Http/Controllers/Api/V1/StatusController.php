<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Status\StoreStatusRequest;
use App\Http\Requests\Api\V1\Status\UpdateStatusRequest;
use App\Http\Resources\Api\V1\StatusResource;
use App\Http\Resources\Api\V1\StatusCollection;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->model = new Status;
        $this->resource = StatusResource::class;
        $this->collection = StatusCollection::class;
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
    public function store(StoreStatusRequest $request)
    {
        $validated = $request->validated();
        $status = Status::create($validated);
        
        return new StatusResource($status);
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
    public function update(UpdateStatusRequest $request, $id)
    {
        $status = Status::findOrFail($id);
        $validated = $request->validated();
        $status->update($validated);
        
        return new StatusResource($status);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return parent::destroy($id);
    }
}