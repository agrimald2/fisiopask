<?php

namespace App\Services;

use App\Models\Workspace;

class WorkspaceService
{
    public function index()
    {
        return Workspace::query()
            ->orderBy('id', 'desc')
            ->get();
    }

    public function create($data)
    {
        return Workspace::create($data);
    }

    public function show($id)
    {
        return Workspace::findOrFail($id);
    }

    public function update(Workspace $workspace, $data)
    {
        return $workspace->update($data);
    }

    public function destroy(Workspace $workspace)
    {
        $workspace->delete();
        return true;
    }
}