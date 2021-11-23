<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\DynamicController;
use App\Models\Family;
use App\Models\Subfamily;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubfamilyController extends DynamicController
{
    protected $resourceName = "Subfamilias";
    protected $resourcePath = "Backend/Subfamilies";
    protected $resourceRoute = "subfamilies";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Subfamily::query()
            ->with('family')
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
        return $this->form(null, [
            'families' => $this->getFamilyOptions(),
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
            'family_id' => 'required|integer',
        ]);

        Subfamily::create($validated);

        return $this->redirectIndex();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subfamily  $subfamily
     * @return \Illuminate\Http\Response
     */
    public function edit(Subfamily $subfamily)
    {
        return $this->form($subfamily, [
            'families' => $this->getFamilyOptions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subfamily  $subfamily
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subfamily $subfamily)
    {
        $validated = $request->validate([
            'name' => 'required',
            'family_id' => 'required|integer',
        ]);

        $subfamily->update($validated);

        return $this->redirectIndex();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subfamily  $subfamily
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subfamily $subfamily)
    {
        $subfamily->delete();

        return $this->redirectIndex();
    }


    private function getFamilyOptions()
    {
        return Family::query()
            ->orderBy('id', 'desc')
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }
}
