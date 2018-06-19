<?php namespace Genetsis\Promotions\Controllers;

use Illuminate\Routing\Controller as BaseController;

class ShowImage extends BaseController
{
    public function index($path, $image, $type) {
        $img_path =  $path . '/'.$image.'.'.$type;

        if (\Storage::exists($img_path)) {

            header('Content-type: '.\Storage::mimeType($img_path).';');
            header("Content-Length: " . \Storage::size($img_path));

            echo \Storage::get($img_path);
        } else {
            abort(404);
        }
    }

}

