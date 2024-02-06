<?php

namespace App\Models\Ads;

use App\Models\Site\Site;
use App\Models\System\Setting\SystemSettingLanguage;
use App\Models\System\Setting\SystemSettingMenuType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ads extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'title',
        'image',
        'sort'
    ];

    public static function addAd($data)
    {
        $site = Site::getSite($data['site_id']);
        $ads = new Ads();
        $ads->site_id = $data['site_id'];
        $ads->system_setting_language_id = $site->system_setting_language_id;
        $ads->title = $data['title'];
        $ads->image = $data['image'];
        $ads->sort = $data['sort'];
        $ads->save();
        return $ads->id;
    }

    public static function editAd($id, $data)
    {
        $site = Site::getSite($data['site_id']);
        self::query()->find($id)->update([
            'site_id' => $data['site_id'],
            'system_setting_language_id' => $site->system_setting_language_id,
            'title' => $data['title'],
            'image' => $data['image'],
            'sort' => $data['sort']
        ]);
    }

    public static function getAd($id)
    {
        return self::query()->find($id);
    }

    public static function getAds($site_id)
    {
        if (!empty($site_id)) {
            return self::query()->where('site_id', $site_id)->orderBy('sort','desc')->orderBy('id','desc')->paginate(20);
        } else {
            return self::query()->orderBy('sort','desc')->orderBy('id','desc')->paginate(20);
        }
    }

    public static function deleteAd($id)
    {
        self::destroy($id);
    }
}
