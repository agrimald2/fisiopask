<?php

namespace App\Services;

use App\Models\Recommendation;

class RecommendationService
{
    public function index()
    {
        return Recommendation::query()
            ->orderBy('id', 'desc')
            ->get();
    }

    public function create($data)
    {
        return Recommendation::create($data);
    }

    public function show($id)
    {
        return Recommendation::findOrFail($id);
    }

    public function update(Recommendation $recommendation, $data)
    {
        return $recommendation->update($data);
    }

    public function destroy(Recommendation $recommendation)
    {
        $recommendation->delete();
        return true;
    }
}