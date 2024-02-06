<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use App\Http\Requests\addMenuRequest;
use App\Models\Category\Category;
use App\Models\Menu\Menu;
use App\Models\Page\Page;
use App\Models\System\Setting\SystemSettingMenuType;
use Illuminate\Http\Request;

class MenuController extends ESUBaseController
{
    /**
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function addMenu(addMenuRequest $request)
    {
        $id = Menu::addMenu($request);
        $data = $this->getMenu($id);
        $code = 1;
        $msg = "添加成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function editMenu(addMenuRequest $request, $id)
    {
        $this->getMenu($id);
        Menu::editMenu($id, $request);
        $data = $this->getMenu($id);
        $code = 1;
        $msg = "修改成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function showMenu($id)
    {
        $data = $this->getMenu($id);
        $code = 1;
        $msg = "获取成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function deleteMenu($id)
    {
        $this->getMenu($id);
        Menu::deleteMenu($id);
        $code = 1;
        $msg = "删除成功";
        return $this->response($code, $data = null, $msg);
    }

    /**
     * @return mixed
     */
    public function listMenu(Request $request, $site_id = null)
    {
        $menus = Menu::getMenus($site_id);
//        print_r($menus);die;
        $data = array();
        if ($menus) {
            foreach ($menus as $menu) {
                if ($menu->menu_type == 0) {
                    unset($menu);
                    continue;
                }
                $menuType = SystemSettingMenuType::getMenuType($menu->menu_type);
                if ($menuType->menu_type_code == 'category') {
                    $category = Category::getCategory($menu->relation_id);
                    if ($category) {
                        $relation = $category->title;
                    } else {
                        $relation = '';
                    }
                } elseif ($menuType->menu_type_code == 'page') {
                    $page = Page::getPage($menu->relation_id);
                    if ($page) {
                        $relation = $page->title;
                    } else {
                        $relation = '';
                    }
                } else {
                    $relation = '';
                }
                $data[] = array(
                    'id' => $menu->id,
                    'title' => $menu->menu_title,
                    'menu_type' => trans('menu-type.' . $menuType->menu_type_code),
                    'relation' => $relation
                );
            }
        }
        $code = 1;
        $msg = "获取成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @throws \App\Exceptions\SystemErrorExcept
     */
    private function getMenu($id)
    {
        $menu = Menu::getMenu($id);
        if (!$menu) {
            $code = 20020;
            $msg = "菜单不存在";
            $this->error($msg, $code);
        }
        return $menu;
    }
}
