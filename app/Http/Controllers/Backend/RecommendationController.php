<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\DynamicController;
use App\Models\Recommendation;
use Illuminate\Http\Request;

class RecommendationController extends DynamicController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $resourceName = "Recomendaciones";
    protected $resourcePath = "Backend/Recommendation";
    protected $resourceRoute = "recommendation";

    public function index(Request $request)
    {
        $model = Recommendation::query()
        ->orderBy('id', 'desc')
        ->paginate(10);

        return $this->grid($model, $request->all(), [
            'enableSearch' => false
        ]);
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
            'recommendation' => 'required',
        ]);

        Recommendation::create($validated);

        return redirect()->route('recommendation.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Recommendation $recommendation)
    {
        return $this->form($recommendation);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recommendation $recommendation)
    {
        $validated = $request->validate([
            'recommendation' => 'required',
        ]);

        $recommendation->update($validated);

        return redirect()->route('recommendation.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recommendation $recommendation)
    {
        $recommendation->delete();

        return redirect()->route('recommendation.index');
    }
}
