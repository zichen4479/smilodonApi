<?php

namespace App\Models\Article;

use App\Models\Category\Category;
use App\Models\Site\Site;
use App\Models\System\Setting\SystemSettingLanguage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'site_id',
        'category_id',
        'system_setting_language_id',
        'title',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'description',
        'thumb',
        'video_url'
    ];

    public static function addArticle($data)
    {
        $article = new Article();
        $article->article_type_id = $data['article_type_id'];
        $article->site_id = $data['site_id'];
        $article->system_setting_language_id = $data['system_setting_language_id'];
        $article->title = $data['title'];
        $article->meta_title = $data['meta_title'];
        $article->meta_keywords = $data['meta_keywords'];
        $article->meta_description = $data['meta_description'];
        $article->description = $data['description'];
        $article->image = $data['image'];
        $article->thumb = $data['thumb'];
        $article->video_type_id = $data['video_type_id'];
        $article->video_url = $data['video_url'];
        $article->sort = $data['sort'];
        $article->save();
        $articleId = $article->id;
        if (!empty($data['category_id'])) {
            foreach ($data['category_id'] as $category) {
                $articleToCategory = new ArticleCategory();
                $articleToCategory->article_id = $articleId;
                $articleToCategory->category_id = $category;
                $articleToCategory->save();
            }
        }
        return $article->id;
    }

    public static function editArticle($id, $data)
    {
        self::query()->where('id', $id)->update([
            'article_type_id' => $data['article_type_id'],
            'site_id' => $data['site_id'],
            'system_setting_language_id' => $data['system_setting_language_id'],
            'title' => $data['title'],
            'meta_title' => $data['meta_title'],
            'meta_keywords' => $data['meta_keywords'],
            'meta_description' => $data['meta_description'],
            'description' => $data['description'],
            'image' => $data['image'],
            'thumb' => $data['thumb'],
            'video_type_id' => $data['video_type_id'],
            'video_url' => $data['video_url'],
            'sort' => $data['sort']
        ]);
        if (!empty($data['category_id'])) {
            $articleToCategories = ArticleCategory::getCategories($id);
            foreach ($articleToCategories as $atc) {
                ArticleCategory::deleteArticleToCategory($atc->id);
            }
            foreach ($data['category_id'] as $category) {
                $articleToCategory = new ArticleCategory();
                $articleToCategory->article_id = $id;
                $articleToCategory->category_id = $category;
                $articleToCategory->save();
            }
        }
    }

    public static function deleteArticle($id)
    {
        self::destroy($id);
    }

    public static function getArticle($id)
    {
        return self::query()->find($id);
    }

    public static function getArticleBySiteId($site_id)
    {
        return self::query()->where('site_id', $site_id)->first();
    }

    public static function getArticlesByCategoryIdAndSiteId($category_id,$site_id)
    {
        return self::query()->select('articles.id','articles.site_id', 'articles.article_type_id','articles.title', 'articles.image','articles.thumb', 'articles.video_url')->leftJoin('article_category','articles.id','=','article_category.article_id')->leftJoin('categories','categories.id','=','article_category.category_id')->where('articles.site_id', $site_id)->where('categories.id', $category_id)->where('article_category.deleted_at','=',null)->orderBy('articles.sort', 'desc')->orderBy('articles.id', 'desc')->paginate(20);
    }

    public static function getArticlesBySiteId($site_id)
    {
        return self::query()->where('site_id', $site_id)->orderBy('sort', 'desc')->orderBy('id', 'desc')->paginate(20);
    }

    public static function getAllArticlesBySiteId($site_id)
    {
        return self::query()->where('site_id', $site_id)->orderBy('sort', 'desc')->orderBy('id', 'desc')->get();
    }

    public static function getArticles($site_id, $limit = 20)
    {
        if (!empty($site_id)) {
            return self::query()->where('site_id', $site_id)->orderByDesc('id')->paginate($limit);
        } else {
            return self::query()->orderByDesc('id')->paginate($limit);
        }
    }

    public static function getArticleByCategoryId($category_id)
    {
        return self::query()->where('category_id', $category_id)->get();
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id', 'id');
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

}
