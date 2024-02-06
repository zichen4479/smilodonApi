<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use App\Models\File\File;
use App\Models\Site\Site;
use App\Models\System\Setting\SystemSettingLanguage;
use Illuminate\Http\Request;

class SiteController extends ESUBaseController
{
    /**
     * @param Request $request
     * @return mixed|void
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function listSite(Request $request)
    {
        $domain = $request->server('HTTP_ORIGIN');;
        if (strpos($domain, 'smilodon') !== false) {
            $is_china_show = 0;
        } else {
            $is_china_show = 1;
        }
        $sites = Site::getSites($is_china_show);
        $data = array();
        foreach ($sites as $site) {
            $language = SystemSettingLanguage::getSystemSettingLanguage($site->system_setting_language_id);
            $file = File::getFile($site->logo);
            $data[] = array(
                'id' => $site->id,
                'language_code' => $language->code,
                'language_id' => $language->id,
                'language_title' => $language->title,
                'site_name' => $site->site_name,
                'domain' => $site->domain,
                'meta_title' => $site->meta_title,
                'meta_keywords' => $site->meta_keywords,
                'meta_description' => $site->meta_description,
                'logo' => config('variable.image_domain') . $file->cdn_url
            );
        }
        $code = 1;
        $msg = "success";
        return $this->response($code, $data, $msg);
    }

    public function showSite($site_id)
    {
        $site = Site::getSite($site_id);
        $language = SystemSettingLanguage::getSystemSettingLanguage($site->system_setting_language_id);
        $file = File::getFile($site->logo);
        $data = array(
            'id' => $site->id,
            'language_code' => $language->code,
            'language_id' => $language->id,
            'site_name' => $site->site_name,
            'domain' => $site->domain,
            'meta_title' => $site->meta_title,
            'meta_keywords' => $site->meta_keywords,
            'meta_description' => $site->meta_description,
            'logo' => config('variable.image_domain') . $file->cdn_url
        );
        $code = 1;
        $msg = "success";
        return $this->response($code, $data, $msg);
    }

    public function showSiteByDomain(Request $request)
    {
        $site = Site::getSiteByDomain($request->domain);
        $language = SystemSettingLanguage::getSystemSettingLanguage($site->system_setting_language_id);
        $file = File::getFile($site->logo);
        $data = array(
            'id' => $site->id,
            'language_code' => $language->code,
            'language_id' => $language->id,
            'site_name' => $site->site_name,
            'domain' => $site->domain,
            'meta_title' => $site->meta_title,
            'meta_keywords' => $site->meta_keywords,
            'meta_description' => $site->meta_description,
            'logo' => config('variable.image_domain') . $file->cdn_url
        );
        $code = 1;
        $msg = "success";
        return $this->response($code, $data, $msg);
    }
}
