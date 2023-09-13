<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    public function storeRate(Request $request)
    {
        $file = $request->file('file');
        $fileName = 'tarifario.pdf';
        $filePath = storage_path('app/public/' . $fileName);

        $file->storeAs('public', $fileName);

        // Additional logic if needed

        return response()->json(['message' => 'File stored successfully']);
    }

    public function seeRatePdf(){
        $filePath = storage_path('app/public/tarifario.pdf');
        return response()->file($filePath);
    }
}
