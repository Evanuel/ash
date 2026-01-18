<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\PaymentMethod\StorePaymentMethodRequest;
use App\Http\Requests\Api\V1\PaymentMethod\UpdatePaymentMethodRequest;
use App\Http\Resources\Api\V1\PaymentMethodResource;
use App\Http\Resources\Api\V1\PaymentMethodCollection;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->model = new PaymentMethod;
        $this->resource = PaymentMethodResource::class;
        $this->collection = PaymentMethodCollection::class;
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
    public function store(StorePaymentMethodRequest $request)
    {
        $validated = $request->validated();
        $paymentMethod = PaymentMethod::create($validated);
        
        return new PaymentMethodResource($paymentMethod);
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
    public function update(UpdatePaymentMethodRequest $request, $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $validated = $request->validated();
        $paymentMethod->update($validated);
        
        return new PaymentMethodResource($paymentMethod);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return parent::destroy($id);
    }
}