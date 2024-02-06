<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use App\Models\Category\Category;
use Illuminate\Http\Request;

class CategoryController extends ESUBaseController
{
    public function listCategory(Request $request, $site_id = null)
    {
        if ($site_id != null) {
            $request->site_id = $site_id;
        }
        $categories = collect($this->getCategories($request->site_id));
        $categoriesData = array();
        $categoriesData['total'] = $categories->count();
        $categoriesData['categories'] = Category::formatCategories($categories);
        $data = $this->pagination($request, $categoriesData);
        return $this->response($code = 1, $data, $msg = trans('system.get_success'));
    }

    private function getCategories($site_id)
    {
        $categories = Category::getAllCategories($site_id)->toArray();
        $categoriesData = array();
        foreach ($categories as $category) {
            $categoriesData[] = array(
                'site_id' => $category['site_id'],
                'system_setting_language_id' => $category['system_setting_language_id'],
                'category_id' => $category['id'],
                'parent_id' => $category['parent_id'],
                'path' => $category['path'],
                'title' => $category['title'],
                'meta_title' => $category['meta_title'],
                'meta_keywords' => $category['meta_keywords'],
                'meta_description' => $category['meta_description'],
                'image' => $category['image'],
                'sort' => $category['sort'],
                'created_at' => $category['created_at'],
                'updated_at' => $category['updated_at'],
            );
        }
        return $categoriesData;
    }
}
