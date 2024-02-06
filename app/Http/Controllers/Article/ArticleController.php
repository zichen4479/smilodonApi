<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use App\Http\Requests\addArticleRequest;
use App\Http\Requests\editArticleRequest;
use App\Models\Article\Article;
use App\Models\Article\ArticleCategory;
use App\Models\Article\ArticleType;
use App\Models\Category\Category;
use App\Models\File\File;
use App\Models\Site\Site;
use App\Models\Video\VideoType;
use Illuminate\Http\Request;

class ArticleController extends ESUBaseController
{
    /**
     * @param addArticleRequest $request
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function addArticle(addArticleRequest $request)
    {
        $sites = Site::getSite($request->site_id);
        $articleAddData = array(
            'article_type_id' => $request->article_type_id,
            'site_id' => $request->site_id,
            'system_setting_language_id' => $sites->system_setting_language_id,
            'category_id' => $request->category_id,
            'title' => trim($request->title),
            'meta_title' => trim($request->meta_title),
            'meta_keywords' => trim($request->meta_keywords),
            'meta_description' => trim($request->meta_description),
            'description' => trim($request->description),
            'image' => $request->image,
            'thumb' => $request->thumb,
            'video_type_id' => $request->video_type_id,
            'video_url' => trim($request->video_url),
            'sort' => $request->sort,
        );
        $id = Article::addArticle($articleAddData);
        $data = $this->getArticle($id);
        $code = 1;
        $msg = '添加成功';
        return $this->response($code, $data, $msg);
    }

    /**
     * @param editArticleRequest $request
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function editArticle(editArticleRequest $request, $id)
    {
        Article::getArticle($id);
        $sites = Site::getSite($request->site_id);
        $articleEditData = array(
            'article_type_id' => $request->article_type_id,
            'site_id' => $request->site_id,
            'category_id' => $request->category_id,
            'system_setting_language_id' => $sites->system_setting_language_id,
            'title' => trim($request->title),
            'meta_title' => trim($request->meta_title),
            'meta_keywords' => trim($request->meta_keywords),
            'meta_description' => trim($request->meta_description),
            'description' => trim($request->description),
            'image' => $request->image,
            'thumb' => $request->thumb,
            'video_type_id' => $request->video_type_id,
            'video_url' => trim($request->video_url),
            'sort' => $request->sort
        );
        Article::editArticle($id, $articleEditData);
        $data = $this->getArticle($id);
        $code = 1;
        $msg = '修改成功';
        return $this->response($code, $data, $msg);
    }

    /**
     * @param Request $request
     * @param null $site_id
     * @param int $limit
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function listArticle(Request $request, $site_id = null, $limit = 20)
    {
        $code = 1;
        $msg = "获取成功";
        $articles = Article::getArticles($site_id, $limit);
        $data = array();
        foreach ($articles->getCollection() as $article) {
            $articleInfo = Article::getArticle($article->id);
            $categories = ArticleCategory::getCategories($articleInfo->id);
            $categoryNameArray = array();
            foreach ($categories as $category) {
                $categoryName = '';
                $categoryInfo = Category::getCategory($category->category_id);
                $categoryNameArray[] = $categoryInfo->title;
            }
            $categoryName = implode(" / ", $categoryNameArray);
            $fileImage = File::getFile($articleInfo->image);
            $fileThumb = File::getFile($articleInfo->thumb);
            $data[] = array(
                'id' => $articleInfo->id,
                'article_type_id' => $articleInfo->article_type_id,
                'category_name' => $categoryName,
                'site_id' => $articleInfo->site_id,
                'system_setting_language_id' => $articleInfo->system_setting_language_id,
                'title' => $articleInfo->title,
                'meta_title' => $articleInfo->meta_title,
                'meta_keywords' => $articleInfo->meta_keywords,
                'meta_description' => $articleInfo->meta_description,
                'sort' => $articleInfo->sort,
                'description' => $articleInfo->description,
                'image' => !empty($fileImage) ? config('variable.image_domain') . $fileImage->cdn_url : '',
                'thumb' => !empty($fileThumb) ? config('variable.image_domain') . $fileThumb->cdn_url : '',
                'video_type_id' => $articleInfo->video_type_id,
                'video_url' => $articleInfo->video_url,
                'deleted_at' => $articleInfo->deleted_at,
                'created_at' => $articleInfo->created_at,
                'updated_at' => $articleInfo->updated_at,
                'site' => $articleInfo->site
            );
        }
        $data = $articles->setCollection(collect($data));
        return $this->response($code, $data, $msg);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function showArticle($id)
    {
        $code = 1;
        $msg = "获取成功";
        $data = $this->getArticle($id);
        return $this->response($code, $data, $msg);
    }

    public function deleteArticle($id)
    {
        Article::deleteArticle($id);
        $code = 1;
        $msg = "删除成功";
        $data = null;
        return $this->response($code, $data, $msg);
    }

    public function listArticleType()
    {
        $articleTypes = ArticleType::getArticleTypes();
        $data = array();
        foreach ($articleTypes as $articleType) {
            $data[] = array(
                'id' => $articleType->id,
                'title' => trans('article-type.' . $articleType->code)
            );
        }
        $code = 1;
        $msg = "获取成功";
        return $this->response($code, $data, $msg);
    }

    public function listVideoType()
    {
        $videoTypes = VideoType::getVideoTypes();
        $data = array();
        foreach ($videoTypes as $videoType) {
            $data[] = array(
                'id' => $videoType->id,
                'title' => trans('video-type.' . $videoType->code)
            );
        }
        $code = 1;
        $msg = "获取成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|void|null
     * @throws \App\Exceptions\SystemErrorExcept
     */
    private function getArticle($id)
    {
        $articleInfo = Article::getArticle($id);
        $data = array();
        if (!$articleInfo) {
            $msg = "文章不存在";
            $code = "20010";
            return $this->error($msg, $code);
        }
        $categories = ArticleCategory::getCategories($articleInfo->id);
        $categoryFormat = array();
        foreach ($categories as $category) {
            $categoryInfo = Category::getCategory($category->category_id);
            $categoryPath = str_replace("0/", "", $categoryInfo->path) . "/" . $category->category_id;
            $categoryFormat[] = explode("/", $categoryPath);
        }

        $fileImage = File::getFile($articleInfo->image);
        $fileThumb = File::getFile($articleInfo->thumb);
        $data = array(
            'id' => $articleInfo->id,
            'article_type_id' => $articleInfo->article_type_id,
            'category_id' => $articleInfo->category_id,
            'category_format' => $categoryFormat,
            'site_id' => $articleInfo->site_id,
            'system_setting_language_id' => $articleInfo->system_setting_language_id,
            'title' => $articleInfo->title,
            'meta_title' => $articleInfo->meta_title,
            'meta_keywords' => $articleInfo->meta_keywords,
            'meta_description' => $articleInfo->meta_description,
            'sort' => $articleInfo->sort,
            'description' => $articleInfo->description,
//            'image' => config('variable.image_domain') . $fileImage->cdn_url,
//            'thumb' => config('variable.image_domain') . $fileThumb->cdn_url,
            'image' => $articleInfo->image,
            'thumb' => $articleInfo->thumb,
            'video_type_id' => $articleInfo->video_type_id,
            'video_url' => $articleInfo->video_url,
            'deleted_at' => $articleInfo->deleted_at,
            'created_at' => $articleInfo->created_at,
            'updated_at' => $articleInfo->updated_at,
            'category' => $articleInfo->category,
            'site' => $articleInfo->site
        );
        return $data;
    }
}
