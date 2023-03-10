<?php

namespace App\Http\Controllers\Backend\Bills;

use App\Http\Controllers\DynamicController;
use Illuminate\Http\Request;
use App\Models\BillsReceiver;

class BillsReceiverController extends DynamicController
{
    protected $resourceName = "Bills Receivers";
    protected $resourcePath = "Backend/Bills/Receivers";
    protected $resourceRoute = "billsreceivers";


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = BillsReceiver::query()
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
            'name' => 'required',
            'document' => 'required',
            'description' => 'required'
        ]);

        BillsReceiver::create($validated);

        return $this->redirectIndex();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BillsReceiver  $receiver
     * @return \Illuminate\Http\Response
     */
    public function edit(BillsReceiver $receiver)
    {
        return $this->form($receiver);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Family  $receiver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BillsReceiver $receiver)
    {
        $validated = $request->validate([
            'name' => 'required',
            'document' => 'required',
            'description' => 'required',
        ]);

        $receiver->update($validated);

        return $this->redirectIndex();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BillsReceiver  $receiver
     * @return \Illuminate\Http\Response
     */
    public function destroy(BillsReceiver $receiver)
    {
        $receiver->delete();

        return $this->redirectIndex();
    }
}
