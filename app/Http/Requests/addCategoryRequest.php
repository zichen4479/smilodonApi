<?php

namespace App\Http\Requests;

use App\Models\Category\Category;
use App\Models\Site\Site;
use App\Models\System\Setting\SystemSettingLanguage;
use Illuminate\Foundation\Http\FormRequest;

class addCategoryRequest extends ApiBaseRequest
{
    protected $code = 10001;
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
        $categories = Category::getAllCategories();
        $categoryIds = array();
        foreach ($categories as $category) {
            $categoryIds[] = $category->id;
        }
        $categoryIds = "0," . implode(",", $categoryIds);
        return [
            'site_id' => 'required|int|in:' . $siteIdsStr,
            'parent_id' => 'required|int|in:' . $categoryIds,
            'title' => 'required|min:1|max:120',
            'meta_title' => 'required|min:3|max:60',
            'meta_keywords' => 'max:155',
            'meta_description' => 'max:160'
        ];
    }

    public function messages()
    {
        return [
            'site_id.required' => trans('category.request_required_site_id'),
            'parent_id.required' => trans('category.request_required_parent_id'),
            'title.required' => trans('category.request_required_title'),
            'meta_title.required' => trans('category.request_required_meta_title'),
            'site_id.int' => trans('category.request_int_site_id'),
            'site_id.in' => trans('category.request_in_site_id'),
            'parent_id.int' => trans('category.request_int_parent_id'),
            'parent_id.in' => trans('category.request_in_parent_id'),
            'title.min' => trans('category.request_min_title'),
            'title.max' => trans('category.request_max_title'),
            'meta_title.min' => trans('category.request_min_meta_title'),
            'meta_title.max' => trans('category.request_max_meta_title'),
            'meta_keywords.max' => trans('category.request_max_meta_keywords'),
            'meta_description.max' => trans('category.request_max_meta_description'),
        ];
    }
}
