<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use App\Http\Requests\AddSiteRequest;
use App\Http\Requests\EditSiteRequest;
use App\Models\Site\Site;
use App\Models\System\Setting\SystemSettingLanguage;
use Illuminate\Http\Request;

class SiteController extends ESUBaseController
{
    /**
     * @param AddSiteRequest $request
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function addSite(AddSiteRequest $request)
    {
        $id = Site::addSite($request->all());
        if (!$id) {
            $code = '20003';
            $msg = "网站添加失败";
            $this->error($msg, $code);
        }
        $data = $this->getSite($id);
        $code = 1;
        $msg = "添加成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param EditSiteRequest $request
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function editSite(EditSiteRequest $request, $id)
    {
        $this->getSite($id);
        Site::editSite($id, $request->all());
        $data = $this->getSite($id);
        $code = 1;
        $msg = "修改成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function listSite()
    {
        $sites = Site::getSitesBack();
        $data = array();
        foreach ($sites as $site) {
            $data[] = $this->getSite($site->id);
        }
        $code = 1;
        $msg = "获取成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function showSite($id)
    {
        $data = $this->getSite($id);
        $code = 1;
        $msg = "获取成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param $id
     * @return array
     * @throws \App\Exceptions\SystemErrorExcept
     */
    private function getSite($id)
    {
        $site = Site::getSite($id);
        if (!$site) {
            $code = 20020;
            $msg = "网站不存在";
            $this->error($msg, $code);
        }
        $language = SystemSettingLanguage::getSystemSettingLanguage($site->system_setting_language_id);
        $siteData = array(
            'id' => $site->id,
            'system_setting_language_id' => $language->id,
            'language_title' => $language->title,
            'site_name' => $site->site_name,
            'domain' => $site->domain,
            'meta_title' => $site->meta_title,
            'meta_keywords' => $site->meta_keywords,
            'meta_description' => $site->meta_description,
            'logo' => $site->logo,
            'banner_text' => $site->banner_text,
            'mail_host' => $site->mail_host,
            'mail_port' => $site->mail_port,
            'mail_username' => $site->mail_username,
            'mail_password' => $site->mail_password,
            'receive_mail_address' => $site->receive_mail_address
        );
        return $siteData;
    }
}
