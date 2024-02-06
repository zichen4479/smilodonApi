<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_setting_language_id',
        'site_name',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'logo',
        'banner_text',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'receive_mail_address'
    ];

    protected $casts = [
        'is_china_show' => 'boolean'
    ];

    public static function getSites($is_china_show = 0)
    {
        return self::query()->where('is_china_show', $is_china_show)->get();
    }

    public static function getSitesBack()
    {
        return self::query()->get();
    }

    public static function getSite($id)
    {
        return self::query()->find($id);
    }

    public static function getSiteByDomain($domain){
        return self::query()->where('domain',$domain)->first();
    }

    public static function addSite($data)
    {
        $site = new Site();
        $site->system_setting_language_id = $data['system_setting_language_id'];
        $site->site_name = $data['site_name'];
        $site->meta_title = $data['meta_title'];
        $site->meta_keywords = $data['meta_keywords'];
        $site->meta_description = $data['meta_description'];
        $site->logo = $data['logo'];
        $site->banner_text = $data['banner_text'];
        $site->mail_host = $data['mail_host'];
        $site->mail_port = $data['mail_port'];
        $site->mail_username = $data['mail_username'];
        $site->mail_password = $data['mail_password'];
        $site->receive_mail_address = $data['receive_mail_address'];
        $site->save();
        return $site->id;
    }

    public static function editSite($id, $data)
    {
        return self::query()->find($id)->update([
            'system_setting_language_id' => $data['system_setting_language_id'],
            'site_name' => $data['site_name'],
            'meta_title' => $data['meta_title'],
            'meta_keywords' => $data['meta_keywords'],
            'meta_description' => $data['meta_description'],
            'logo' => $data['logo'],
            'banner_text' => $data['banner_text'],
            'mail_host' => $data['mail_host'],
            'mail_port' => $data['mail_port'],
            'mail_username' => $data['mail_username'],
            'mail_password' => $data['mail_password'],
            'receive_mail_address' => $data['receive_mail_address'],
        ]);
    }
}
