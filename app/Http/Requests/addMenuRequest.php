<?php

namespace App\Http\Requests;

use App\Models\Category\Category;
use App\Models\Page\Page;
use App\Models\Site\Site;
use App\Models\System\Setting\SystemSettingMenuType;
use Illuminate\Foundation\Http\FormRequest;

class addMenuRequest extends ApiBaseRequest
{
    protected $code = 10008;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $sites = Site::getSitesBack();
        $siteIds = array();
        foreach ($sites as $site) {
            $siteIds[] = $site->id;
        }
        $siteIdsStr = implode(",", $siteIds);
        $menuTypes = SystemSettingMenuType::all();
        $menuTypeIds = array();
        foreach ($menuTypes as $menuType) {
            $menuTypeIds[] = $menuType->id;
        }
        $menuTypeIdsStr = implode(",", $menuTypeIds);
        $siteData = Site::getSite($this->input('site_id'));
        if ($this->input('menu_type') == 1) {
            $categories = Category::getAllCategories($siteData->system_setting_lanugage_id);
            $relationIds = array();
            foreach ($categories as $category) {
                $relationIds[] = $category->id;
            }
        } elseif ($this->input('menu_type') == 2) {
            $pages = Page::getPages($siteData->system_setting_lanugage_id);
            $relationIds = array();
            foreach ($pages as $page) {
                $relationIds[] = $page->id;
            }
        } else {
            $relationIds = array();
        }
        $relationIdsStr = implode(",", $relationIds);
        return [
            'site_id' => 'required|in:' . $siteIdsStr,
            'menu_type' => 'required|in:' . $menuTypeIdsStr,
            'menu_title' => 'required|min:1|max:64',
            'relation_id' => 'required|int|in:' . $relationIdsStr
        ];
    }
    public function messages()
    {
        return [
            'menu_title.required' => trans('menu.required_menu_title')
        ];
    }
}
