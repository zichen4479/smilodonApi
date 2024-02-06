<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use App\Models\Ads\Ads;
use App\Models\File\File;
use App\Models\Site\Site;
use Illuminate\Http\Request;

class AdsController extends ESUBaseController
{
    public function listAds(Request $request, $site_id = null)
    {
        if ($site_id != null) {
            $request->site_id = $site_id;
        }
        $ads = Ads::getAds($request->site_id);
        $images = array();
        foreach ($ads as $ad) {
            $image = File::getFile($ad->image);
            $images[] = config('variable.image_domain') . $image->cdn_url;
        }
        $site = Site::getSite($request->site_id);
        $data = array(
            'title' => $site->banner_text,
            'images' => $images
        );
        $code = 1;
        $msg = 'success';
        return $this->response($code, $data, $msg);
    }
}
