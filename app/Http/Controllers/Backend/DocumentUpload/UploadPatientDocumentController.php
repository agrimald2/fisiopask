<?php

namespace App\Http\Controllers\Backend\DocumentUpload;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\MedicalHistoryFile;
use Facade\FlareClient\Http\Response;

class UploadPatientDocumentController extends Controller
{
    public function upload(Request $request, $id)
    {
        //Generate random file name and upload to public dir
        $originalname = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = $originalname.'_'.date('d-m-y-h-i-s').'.'.$request->file('file')->extension();
        Storage::putFileAs('public', $request->file('file'), $filename);

        MedicalHistoryFile::create([
            'filename' => $filename,
            'history_group_id' => $id,
        ]);

        return redirect()->back();
    }

    public function download($filename)
    {
        $file = public_path().'\\storage\\'.$filename;

        $headers = [
            'Content-Type' => 'application/pdf',
        ];

        //Storage::download($file);
        //$file = Storage::disk('public')->get($filename);
        return response()->download($file, $filename, $headers);
    }
}
