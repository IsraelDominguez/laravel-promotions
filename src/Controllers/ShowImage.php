<?php namespace Genetsis\Promotions\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ShowImage extends BaseController
{
    public function index(Request $request) {
        $img_path = $request->input('img');

        if (\Storage::exists($img_path)) {

            header('Content-type: '.\Storage::mimeType($img_path).';');
            header("Content-Length: " . \Storage::size($img_path));

            echo \Storage::get($img_path);
        } else {
            echo '<img src="'.$img_path.'">';
        }
    }

}

