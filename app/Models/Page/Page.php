<?php

namespace App\Models\Page;

use App\Models\Site\Site;
use App\Models\System\Setting\SystemSettingLanguage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'site_id',
        'system_setting_language_id',
        'title',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'description',
        'image'
    ];

    public static function getPages($site_id = null)
    {
        if (!empty($site_id)) {
            return self::query()->where('site_id', $site_id)->get();
        } else {
            return self::query()->get();
        }
    }

    public static function getPage($id)
    {
        return self::query()->find($id);
    }

    public static function addPage($data)
    {
        $site = Site::getSite($data['site_id']);
        $page = new Page();
        $page->site_id = $data['site_id'];
        $page->system_setting_language_id = $site->system_setting_language_id;
        $page->title = $data['title'];
        $page->meta_title = $data['meta_title'];
        $page->meta_keywords = $data['meta_keywords'];
        $page->meta_description = $data['meta_description'];
        $page->description = $data['description'];
        $page->image = $data['image'];
        $page->save();
        return $page->id;
    }

    public static function editPage($id, $data)
    {
        $site = Site::getSite($data['site_id']);
        return self::query()->find($id)->update([
            'site_id' => $data['site_id'],
            'system_setting_language_id' => $site->system_setting_language_id,
            'title' => $data['title'],
            'meta_title' => $data['meta_title'],
            'meta_keywords' => $data['meta_keywords'],
            'meta_description' => $data['meta_description'],
            'description' => $data['description'],
            'image' => $data['image']
        ]);
    }

    public static function deletePage($id)
    {
        return self::destroy($id);
    }
}
