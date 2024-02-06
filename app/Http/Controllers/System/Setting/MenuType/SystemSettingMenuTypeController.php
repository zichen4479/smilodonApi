<?php

namespace App\Http\Controllers\System\Setting\MenuType;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use App\Models\System\Setting\SystemSettingMenuType;
use Illuminate\Http\Request;

class SystemSettingMenuTypeController extends ESUBaseController
{
    public function listMenuType()
    {
        $code = 1;
        $msg = "获取成功";
        $menuTypes = SystemSettingMenuType::all();
        $data = array();
        foreach ($menuTypes as $menuType) {
            $data[] = array(
                'id' => $menuType->id,
                'title' => trans('menu-type.' . $menuType->menu_type_code),
                'code' => $menuType->code,
                'created_at' => $menuType->created_at,
                'updated_at' => $menuType->updated_at
            );
        }
        return $this->response($code, $data, $msg);
    }
}
