<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use App\Models\File\File;
use Illuminate\Http\Request;

class FileController extends ESUBaseController
{
    public function showFile(Request $request,$id){
        $file = File::getFile($id);
        $data = $file;
        $code = 1;
        $msg = 'success';
        return $this->response($code, $data, $msg);
    }
}
