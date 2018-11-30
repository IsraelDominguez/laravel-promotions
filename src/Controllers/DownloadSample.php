<?php namespace Genetsis\Promotions\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class DownloadSample extends BaseController
{
    public function index($file) {
        $file_path =  'samples/'.$file.'.csv';

        if (Storage::exists($file_path)) {

            header('Content-type: '.\Storage::mimeType($file_path).';');
            header("Content-Length: " . \Storage::size($file_path));
            header("Content-Disposition: attachment; filename=".$file.".csv");


            return \Response::make(\Storage::get($file_path), 200);
        } else {
            abort(404);
        }
    }

}

