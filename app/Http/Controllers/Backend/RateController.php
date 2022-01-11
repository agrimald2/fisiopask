<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\DynamicController;
use App\Models\Rate;
use App\Models\Subfamily;
use Illuminate\Http\Request;

class RateController extends DynamicController
{
    protected $resourceName = "Tarifas";
    protected $resourcePath = "Backend/Rates";
    protected $resourceRoute = "rates";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Rate::query()
            ->orderBy('id', 'desc')
            ->with('subfamily')
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
        return $this->form(['stock' => 2], [
            'subfamilies' => $this->getSubfamilyOptions(),
        ]);
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
            'price' => 'required|numeric',
            'is_product' => 'nullable|boolean',
            'stock' => 'required|numeric',
            'subfamily_id' => 'required|integer',
        ]);

        Rate::create($validated);

        return $this->redirectIndex();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function edit(Rate $rate)
    {
        return $this->form($rate, [
            'subfamilies' => $this->getSubfamilyOptions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rate $rate)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'numeric',
            'is_product' => 'nullable|boolean',
            'stock' => 'required|integer',

            'subfamily_id' => 'required|integer',
        ]);

        $rate->update($validated);

        return $this->redirectIndex();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rate $rate)
    {
        $rate->delete();

        return $this->redirectIndex();
    }


    private function getSubfamilyOptions()
    {
        return Subfamily::query()
            ->orderBy('id', 'desc')
            ->get()
            ->mapWithKeys(function ($subfamily) {
                $text = $subfamily->family->name . ' - ' . $subfamily->name;
                return [
                    $subfamily->id => $text
                ];
            })
            ->toArray();
    }
}
