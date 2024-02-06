<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'system_setting_language_id',
        'parent_id',
        'path',
        'title',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'image',
        'sort'
    ];

    public static function addCategory($data)
    {
        $category = new Category();
        $category->site_id = $data['site_id'];
        $category->system_setting_language_id = $data['system_setting_language_id'];
        $category->parent_id = $data['parent_id'];
        $category->path = $data['path'];
        $category->title = $data['title'];
        $category->meta_title = $data['meta_title'];
        $category->meta_keywords = $data['meta_keywords'];
        $category->meta_description = $data['meta_description'];
        $category->image = $data['image'];
        $category->sort = $data['sort'];
        $category->save();
        return $category->id;
    }

    public static function editCategory($category_id, $data)
    {
        self::query()->where('id', '=', $category_id)->first()->update([
                'site_id' => $data['site_id'],
                'system_setting_language_id' => $data['system_setting_language_id'],
                'parent_id' => $data['parent_id'],
                'path' => $data['path'],
                'title' => $data['title'],
                'meta_title' => $data['meta_title'],
                'meta_keywords' => $data['meta_keywords'],
                'meta_description' => $data['meta_description'],
                'image' => $data['image'],
                'sort' => $data['sort']
            ]
        );
    }

    /**
     * @param $parent_id
     * @return \Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public static function getCategoryParentId($parent_id)
    {
        $data = self::query()->where('id', '=', $parent_id)->first();
        return $data;
    }

    public static function getHasParent($category_id){
        return self::query()->where('parent_id',$category_id)->get();
    }

    /**
     * @param $system_setting_language_id
     * @param $parent_id
     * @param $title
     * @return \Illuminate\Database\Eloquent\Builder|Model|object|null
     */

    public static function getCategoryWithSiteIdAndParentId($site_id, $parent_id, $title)
    {
        $data = self::query()->where('parent_id', '=', $parent_id)->where('site_id', '=', $site_id)->where('title', '=', $title)->first();
        return $data;
    }

    /**
     * @param $category_id
     * @param $system_setting_language_id
     * @param $parent_id
     * @param $title
     * @return \Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public static function getCategoryWithSiteIdAndParentIdNotInSelf($category_id, $site_id, $parent_id, $title)
    {
        $data = self::query()->where('parent_id', '=', $parent_id)->where('site_id', '=', $site_id)->where('title', '=', $title)->where('id', '!=', $category_id)->first();
        return $data;
    }

    public static function deleteCategory($id)
    {
        self::destroy($id);
    }

    public static function getCategory($id)
    {
        return Category::query()->find($id);
    }

    public static function getAllCategories($site_id = null)
    {
        if (!empty($site_id)) {
            return self::query()->where('site_id', $site_id)->get();
        } else {
            return self::query()->get();
        }

    }

    public static function formatCategories(&$collection, $parentId = '0', &$item = null, $name = 'children')
    {
        $tree = [];
        foreach ($collection as $key => $value) {
            if ($value['parent_id'] == $parentId) {
                self::shiftCollection($collection, $value, $key);
                if ($item) $item[$name][] = $value;
                else $tree[] = $value;
            }
        }
        return $tree;
    }

    public static function shiftCollection(&$collection, &$value, $key)
    {
        unset($collection[$key]);
        self::formatCategories($collection, $value['category_id'], $value);
    }
}
