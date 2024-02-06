<?php

namespace App\Http\Controllers\System\Setting\Language;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use App\Models\System\Setting\SystemSettingLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redis;

class SystemSettingLanguageController extends ESUBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllSystemSettingLanguage()
    {
        $data = SystemSettingLanguage::query()->paginate(config('variable.paginate_limit'));
        $code = 1;
        $msg = '';
        return $this->response($code, $data, $msg);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function showSystemSettingLanguage($query)
    {
        $data = SystemSettingLanguage::filterShow($query);
        $code = 1;
        $msg = trans('system.get_success');
        return $this->response($code, $data, $msg);
    }
}
