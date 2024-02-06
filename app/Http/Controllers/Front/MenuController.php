<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use App\Models\Menu\Menu;
use App\Models\Site\Site;
use App\Models\System\Setting\SystemSettingLanguage;
use App\Models\System\Setting\SystemSettingMenuType;
use Illuminate\Http\Request;

class MenuController extends ESUBaseController
{
    public function listMenu(Request $request, $site_id = null)
    {
        if ($site_id != null) {
            $request->site_id = $site_id;
        }
        $menus = Menu::getMenus($request->site_id);
        $data = array();
        foreach ($menus as $menu) {
            $site = Site::getSite($menu->site_id);
            $language = SystemSettingLanguage::getSystemSettingLanguage($menu->system_setting_language_id);
            $menuType = SystemSettingMenuType::getMenuType($menu->menu_type);
            $data[] = array(
                'id' => $menu->id,
//                'site' => $site,
                'language' => $language,
                'menu_type' => $menuType,
                'menu_title' => $menu->menu_title,
                'relation_id' => $menu->relation_id,
                'path' => $menu->path,
                'sort' => $menu->sort
            );
        }

        $code = 1;
        $msg = 'success';
        return $this->response($code, $data, $msg);
    }
}
