<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Bank\StoreBankRequest;
use App\Http\Requests\Api\V1\Bank\UpdateBankRequest;
use App\Http\Resources\Api\V1\BankResource;
use App\Http\Resources\Api\V1\BankCollection;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->model = new Bank;
        $this->resource = BankResource::class;
        $this->collection = BankCollection::class;
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
    public function store(StoreBankRequest $request)
    {
        $validated = $request->validated();
        $bank = Bank::create($validated);
        
        return new BankResource($bank);
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
    public function update(UpdateBankRequest $request, $id)
    {
        $bank = Bank::findOrFail($id);
        $validated = $request->validated();
        $bank->update($validated);
        
        return new BankResource($bank);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return parent::destroy($id);
    }
}