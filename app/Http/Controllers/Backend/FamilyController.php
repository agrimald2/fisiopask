<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\DynamicController;
use App\Models\Family;
use Illuminate\Http\Request;

class FamilyController extends DynamicController
{
    protected $resourceName = "Familias";
    protected $resourcePath = "Backend/Families";
    protected $resourceRoute = "families";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $model = Family::query()
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

        Family::create($validated);

        return $this->redirectIndex();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function edit(Family $family)
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
    public function update(Request $request, Family $family)
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
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function destroy(Family $family)
    {
        $family->delete();

        return $this->redirectIndex();
    }
}
