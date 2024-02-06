<?php

namespace App\Http\Controllers\Ads;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use App\Http\Requests\addAdRequest;
use App\Models\Ads\Ads;
use App\Models\File\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdsController extends ESUBaseController
{
    /**
     * @param Request $request
     * @param null $site_id
     * @return mixed
     */
    public function listAd(Request $request, $site_id = null){
        $ads = Ads::getAds($site_id);
        $data = array();
        foreach ($ads->getCollection() as $ad){
            $file = File::getFile($ad->image);
            $data[] = array(
                'id' => $ad->id,
                'title' => $ad->title,
                'thumb' => config('variable.image_domain') .$file->cdn_url,
                'created_at' => $ad->created_at
            );
        }
        $data = $ads->setCollection(collect($data));
        $code = 1;
        $msg = "获取成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function showAd($id){
        $data = $this->getAd($id);
        $code = 1;
        $msg = "获取成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function addAd(addAdRequest $request){
        $id = Ads::addAd($request);
        $data = $this->getAd($id);
        $code = 1;
        $msg = "添加成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function editAd(addAdRequest $request,$id){
        $this->getAd($id);
        Ads::editAd($id, $request);
        $data = $this->getAd($id);
        $code = 1;
        $msg = "修改成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function deleteAd($id){
        $this->getAd($id);
        Ads::deleteAd($id);
        $code = 1;
        $msg = "删除成功";
        return $this->response($code, $data = null, $msg);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @throws \App\Exceptions\SystemErrorExcept
     */
    private function getAd($id)
    {
        $ad = Ads::getAd($id);
        if (!$ad) {
            $code = 20020;
            $msg = "广告不存在";
            $this->error($msg, $code);
        }
        return $ad;
    }
}
