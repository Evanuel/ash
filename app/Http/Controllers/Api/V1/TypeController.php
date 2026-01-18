<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Type\StoreTypeRequest;
use App\Http\Requests\Api\V1\Type\UpdateTypeRequest;
use App\Http\Resources\Api\V1\TypeResource;
use App\Http\Resources\Api\V1\TypeCollection;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->model = new Type;
        $this->resource = TypeResource::class;
        $this->collection = TypeCollection::class;
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
    public function store(StoreTypeRequest $request)
    {
        $validated = $request->validated();
        $type = Type::create($validated);
        
        return new TypeResource($type);
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
    public function update(UpdateTypeRequest $request, $id)
    {
        $type = Type::findOrFail($id);
        $validated = $request->validated();
        $type->update($validated);
        
        return new TypeResource($type);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return parent::destroy($id);
    }
}