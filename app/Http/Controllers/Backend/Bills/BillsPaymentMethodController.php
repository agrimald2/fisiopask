<?php

namespace App\Http\Controllers\Backend\Bills;

use App\Http\Controllers\DynamicController;
use Illuminate\Http\Request;
use App\Models\BillsPaymentMethod;

class BillsPaymentMethodController extends DynamicController
{
    protected $resourceName = "Bills Payment Method";
    protected $resourcePath = "Backend/Bills/PaymentMethods";
    protected $resourceRoute = "billspaymentmethods";


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = BillsPaymentMethod::query()
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->grid($model, [], ['enableSearch' => false]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->form();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        BillsPaymentMethod::create($validated);

        return $this->redirectIndex();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BillsPaymentMethod  $family
     * @return \Illuminate\Http\Response
     */
    public function edit(BillsPaymentMethod $family)
    {
        return $this->form($family);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BillsPaymentMethod $family)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        $family->update($validated);

        return $this->redirectIndex();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BillsPaymentMethod  $family
     * @return \Illuminate\Http\Response
     */
    public function destroy(BillsPaymentMethod $family)
    {
        $family->delete();

        return $this->redirectIndex();
    }
}
