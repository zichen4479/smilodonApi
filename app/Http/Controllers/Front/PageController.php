<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use App\Models\Page\Page;
use Illuminate\Http\Request;

class PageController extends ESUBaseController
{
    public function showPage(Request $request,$page_id){
        $page = Page::getPage($page_id);
        $data = $page;
        $code = 1;
        $msg = 'success';
        return $this->response($code, $data, $msg);
    }
}
