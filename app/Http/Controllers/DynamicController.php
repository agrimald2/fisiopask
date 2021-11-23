<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DynamicController extends Controller
{

    protected $resourceName;
    protected $resourcePath;
    protected $resourceRoute;

    protected function redirectIndex($routeParameters = [])
    {
        return redirect()->route($this->resourceRoute . ".index", $routeParameters);
    }

    protected function grid($model, $parameters = [], $options = [], $routeParameters = [])
    {
        $data = [
            'model' => $model->items(),

            'links' => $model->linkCollection(),

            'parameters' => $parameters,

            'title' => $this->resourceName,

            'create' => route($this->resourceRoute . ".create", $routeParameters),

            'grid' => $this->resourcePath . '/grid.js',
        ];

        return inertia('Backend/Dynamic/Grid', array_merge($data, $options));
    }


    protected function form($model = null, $options = [], $routeParameters = [])
    {
        $data = [
            'title' => [
                'resource' => $this->resourceName,
                'action' => $model ? 'Editar' : 'Crear',
                'url' => route($this->resourceRoute . '.index', $routeParameters),
            ],

            'form' => $this->resourcePath . '/form.js',
            'model' => $model,
        ];

        return inertia('Backend/Dynamic/Form', array_merge($data, $options));
    }
}
