<?php

namespace App\Models\Menu;

use App\Models\Site\Site;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'menu_type',
        'menu_title',
        'relation_id',
        'sort'
    ];

    public static function addMenu($data)
    {
        $site = Site::getSite($data['site_id']);
        $menu = new Menu();
        $menu->site_id = $data['site_id'];
        $menu->system_setting_language_id = $site->system_setting_language_id;
        $menu->menu_type = $data['menu_type'];
        $menu->menu_title = $data['menu_title'];
        $menu->relation_id = $data['relation_id'];
        $menu->sort = $data['sort'];
        $menu->save();
        return $menu->id;
    }

    public static function editMenu($id, $data)
    {
        $site = Site::getSite($data['site_id']);
        self::query()->find($id)->update([
            'site_id' => $data['site_id'],
            'system_setting_language_id' => $site->system_setting_language_id,
            'menu_type' => $data['menu_type'],
            'menu_title' => $data['menu_title'],
            'relation_id' => $data['relation_id'],
            'sort' => $data['sort']
        ]);
    }

    public static function deleteMenu($id)
    {
        return self::destroy($id);
    }

    public static function getMenu($id)
    {
        return self::query()->find($id);
    }

    public static function getMenus($site_id)
    {
        if (!empty($site_id)) {
            return self::query()->where('site_id', $site_id)->orderBy('sort','asc')->get();
        } else {
            return self::query()->get();
        }
    }

}
