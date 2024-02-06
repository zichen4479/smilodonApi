<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use App\Models\Article\Article;
use App\Models\Article\ArticleCategory;
use App\Models\Article\ArticleType;
use App\Models\File\File;
use App\Models\Video\VideoType;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class ArticleController extends ESUBaseController
{
    public function listArticle(Request $request, $site_id = null)
    {
        if ($site_id != null) {
            $request->site_id = $site_id;
        }
        if (isset($request->category_id) && !empty($request->category_id)) {
            $articles = Article::getArticlesByCategoryIdAndSiteId($request->category_id, $request->site_id);
        } else {
            $articles = Article::getArticlesBySiteId($request->site_id);
        }
        $data = array();
        foreach ($articles->getCollection() as $article) {
            $articleType = ArticleType::getArticleType($article->article_type_id);
            $image = File::getFile($article->image);
            $thumb = File::getFile($article->thumb);
            $imageUrl = !empty($image) ? config('variable.image_domain') . $image->cdn_url : '';
            $originalImageUrl = config('variable.image_domain') . $image['original_image'];
            $thumbImageUrl = !empty($thumb) ? config('variable.image_domain') . $thumb->cdn_url : '';
            $imageWidth = $image['original_width'];
            $imageHeight = $image['original_height'];
            $videoType = VideoType::getVideoType($article->video_type_id);
            if (empty($videoType)) {
                $videoTypeCode = '';
            } else {
                $videoTypeCode = $videoType->code;
            }
            $data[] = array(
                'id' => $article->id,
                'article_type' => $articleType->code,
                'category_id' => $article->category_id,
                'site_id' => $article->site_id,
                'title' => $article->title,
                'meta_title' => $article->meta_title,
                'meta_keywords' => $article->meta_keywords,
                'meta_description' => $article->meta_description,
                'image' => $imageUrl,
                'thumb' => $thumbImageUrl,
                'image_width' => $imageWidth,
                'image_height' => $imageHeight,
                'video_type' => $videoTypeCode,
                'video_url' => $article->video_url,
                'sort' => $article->sort
            );
        }
        $data = $articles->setCollection(collect($data));
        $code = 1;
        $msg = 'success';
        return $this->response($code, $data, $msg);
    }

    public function showArticle(Request $request, $id)
    {
        $article = Article::getArticle($id);
        $articleType = ArticleType::getArticleType($article->article_type_id);
        $image = File::getFile($article->image);
        $thumb = File::getFile($article->thumb);
        $imageUrl = config('variable.image_domain') . $image->cdn_url;
        $originalImageUrl = config('variable.image_domain') . $image['original_image'];
        $thumbImageUrl = config('variable.image_domain') . $thumb->cdn_url;
        $imageWidth = $image['original_width'];
        $imageHeight = $image['original_height'];
        $videoType = VideoType::getVideoType($article->video_type_id);
        if (empty($videoType)) {
            $videoTypeCode = '';
        } else {
            $videoTypeCode = $videoType->code;
        }
        $data = array(
            'id' => $article->id,
            'article_type' => $articleType->code,
            'category_id' => $article->category_id,
            'site_id' => $article->site_id,
            'title' => $article->title,
            'meta_title' => $article->meta_title,
            'meta_keywords' => $article->meta_keywords,
            'meta_description' => $article->meta_description,
            'image' => $imageUrl,
            'thumb' => $thumbImageUrl,
            'image_width' => $imageWidth,
            'image_height' => $imageHeight,
            'video_type' => $videoTypeCode,
            'video_url' => $article->video_url
        );
        $code = 1;
        $msg = 'success';
        return $this->response($code, $data, $msg);
    }
}
