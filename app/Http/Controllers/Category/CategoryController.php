<?php

namespace App\Http\Controllers\Category;

use App\Exceptions\ESUException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use App\Http\Requests\addCategoryRequest;
use App\Http\Requests\editCategoryRequest;
use App\Models\Article\Article;
use App\Models\Article\ArticleCategory;
use App\Models\Category\Category;
use App\Models\Site\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoryController extends ESUBaseController
{
    public function listCategory(Request $request, $site_id = null)
    {
        $categories = collect($this->getCategories($site_id));
        $categoriesData = array();
        $categoriesData['total'] = $categories->count();
        $categoriesData['categories'] = Category::formatCategories($categories);
        $data = $this->pagination($request, $categoriesData);
        return $this->response($code = 1, $data, $msg = trans('system.get_success'));
    }

    /**
     * @param Request $request
     * @return mixed|void
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function addCategory(addCategoryRequest $request)
    {
        if ($request->parent_id != 0) {
            $categoryParentInformation = Category::getCategoryParentId($request->parent_id)->toArray();
            // 校验parent_id是否为正确的值
            if (empty($categoryParentInformation)) {
                $code = 20371;
                $msg = trans('category.error_empty_parent_category');
                return $this->error($msg, $code);
            }
        }

        // 当system_setting_language_id和parent_id相同时，title不允许重复
        if (!empty(Category::getCategoryWithSiteIdAndParentId($request->site_id, $request->parent_id, $request->title))) {
            $code = 20372;
            $msg = trans('category.error_duplicate_category');
            return $this->error($msg, $code);
        }
        // 算出category path
        if ($request->parent_id != 0) {
//            // * 添加子分类时，子分类语言必须与父分类一致
//            if ($request->system_setting_language_id != $categoryParentInformation['system_setting_language_id']) {
//                $code = 20380;
//                $msg = trans('category.error_subcategory_language');
//                return $this->error($msg, $code);
//            }
            $path = $categoryParentInformation['path'] . "/" . $request->parent_id;
        } else {
            $path = 0;
        }
        $site = Site::getSite($request->site_id);
        DB::beginTransaction();
        try {
            $dataAdd = array(
                'site_id' => $request->site_id,
                'system_setting_language_id' => $site->system_setting_language_id,
                'parent_id' => $request->parent_id,
                'path' => $path,
                'title' => trim($request->title),
                'meta_title' => trim($request->meta_title),
                'meta_keywords' => trim($request->meta_keywords),
                'meta_description' => trim($request->meta_description),
                'image' => $request->image,
                'sort' => $request->sort
            );
            $categoryId = Category::addCategory($dataAdd);
            DB::commit();
            $data = $this->getCategory($categoryId);
        } catch (ESUException $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            $msg = trans('category.error_add');
            $code = 20001;
            return $this->error($msg, $code);
        }
        $code = 1;
        $msg = trans('category.success_add');
        return $this->response($code, $data, $msg);
    }

    /**
     * @param editCategoryRequest $request
     * @param $category_id
     * @return mixed|void
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function editCategory(editCategoryRequest $request, $category_id)
    {
        $merchantCategory = $this->getCategory($category_id);
        if (empty($merchantCategory)) {
            $code = 20374;
            $msg = trans('merchant-category.error_empty_category');
            return $this->error($msg, $code);
        }
        if ($request->parent_id != 0) {
            $categoryParentInformation = Category::getCategoryParentId($request->parent_id)->toArray();
            // 校验parent_id是否为正确的值
            if (empty($categoryParentInformation)) {
                $code = 20371;
                $msg = trans('merchant-category.error_empty_parent_category');
                return $this->error($msg, $code);
            }
        }
        // 当system_setting_language_id和parent_id相同时，title不允许重复
        if (!empty(Category::getCategoryWithSiteIdAndParentIdNotInSelf($category_id, $request->site_id, $request->parent_id, $request->title))) {
            $code = 20372;
            $msg = trans('category.error_duplicate_category');
            return $this->error($msg, $code);
        }

        // 算出category path
        if ($request->parent_id != 0) {
            $path = $categoryParentInformation['path'] . "/" . $request->parent_id;
        } else {
            $path = 0;
        }
        $site = Site::getSite($request->site_id);
        DB::beginTransaction();
        try {
            $dataEdit = array(
                'parent_id' => $request->parent_id,
                'path' => $path,
                'title' => trim($request->title),
                'meta_title' => trim($request->meta_title),
                'meta_keywords' => trim($request->meta_keywords),
                'meta_description' => trim($request->meta_description),
                'image' => $request->image,
                'sort' => $request->sort,
                'site_id' => $request->site_id,
                'system_setting_language_id' => $site->system_setting_language_id
            );
            Category::editCategory($category_id, $dataEdit);
            DB::commit();
            $data = $this->getCategory($category_id);
        } catch (ESUException $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            $msg = trans('category.error_edit');
            $code = 20001;
            return $this->error($msg, $code);
        }
        $code = 1;
        $msg = trans('category.success_edit');
        return $this->response($code, $data, $msg);
    }

    /**
     * @param $category_id
     * @return mixed|void
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function deleteCategory($category_id)
    {
        $category = $this->getCategory($category_id);
        if (empty($category)) {
            $code = 20374;
            $msg = trans('category.error_empty_category');
            return $this->error($msg, $code);
        }
        // 查看是否被引用
        $article = ArticleCategory::getArticles($category_id);
        if ($article->count() != 0) {
            $code = 20082;
            $msg = "分类被使用，禁止删除";
            return $this->error($msg, $code);
        }
        // 有子集时不允许被删除
        $hasChild = Category::getHasParent($category_id);
        if ($hasChild->count() != 0) {
            $code = 20083;
            $msg = "有子分类，禁止删除";
            return $this->error($msg, $code);
        }
        DB::beginTransaction();
        try {
            category::deleteCategory($category['id']);
            DB::commit();
        } catch (ESUException $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            $msg = trans('category.error_delete');
            $code = 20001;
            return $this->error($msg, $code);
        }
        $data = null;
        $msg = trans('category.success_delete');
        return $this->response($code = 1, $data, $msg);
    }

    private function getCategory($id)
    {
        return Category::getCategory($id);
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
