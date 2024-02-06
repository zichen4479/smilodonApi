<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use App\Http\Requests\AddPageRequest;
use App\Http\Requests\EditPageRequest;
use App\Models\Page\Page;
use App\Models\Site\Site;
use App\Models\System\Setting\SystemSettingLanguage;
use Illuminate\Http\Request;

class PageController extends ESUBaseController
{
    /**
     * @param AddPageRequest $request
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function addPage(AddPageRequest $request)
    {
        $id = Page::addPage($request);
        $data = $this->getPage($id);
        $code = 1;
        $msg = "添加成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param EditPageRequest $request
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function editPage(EditPageRequest $request, $id)
    {
        $this->getPage($id);
        Page::editPage($id, $request);
        $data = $this->getPage($id);
        $code = 1;
        $msg = "修改成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function showPage($id)
    {
        $data = $this->getPage($id);
        $code = 1;
        $msg = "获取成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function deletePage($id)
    {
        $this->getPage($id);
        Page::deletePage($id);
        $code = 1;
        $msg = "删除成功";
        return $this->response($code, $data = null, $msg);
    }

    /**
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function listPage(Request $request, $site_id = null)
    {
        $data = $this->getPages($site_id);
        $code = 1;
        $msg = "获取成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param $language
     * @return array
     * @throws \App\Exceptions\SystemErrorExcept
     */
    private function getPages($site_id)
    {
        $pages = Page::getPages($site_id);
        $data = array();
        foreach ($pages as $page) {
            $data[] = $this->getPage($page->id);
        }
        return $data;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @throws \App\Exceptions\SystemErrorExcept
     */
    private function getPage($id)
    {
        $page = Page::getPage($id);
        if (!$page) {
            $code = 20020;
            $msg = "网站不存在";
            $this->error($msg, $code);
        }
        $site = Site::getSite($page->site_id);
        $language = SystemSettingLanguage::getSystemSettingLanguage($page->system_setting_language_id);
        $data = array(
            'id' => $page->id,
            'site_id' => $site->id,
            'site_name' => $site->site_name,
            'language' => $language,
            'title' => $page->title,
            'meta_title' => $page->meta_title,
            'meta_keywords' => $page->meta_keywords,
            'meta_description' => $page->meta_description,
            'description' => $page->description,
            'image' => $page->id,
            'created_at' => $page->created_at,
            'updated_at' => $page->updated_at
        );
        return $data;
    }
}
