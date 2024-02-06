<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'article_category';

    public static function getCategories($article_id)
    {
        return self::query()->where('article_id', $article_id)->get();
    }

    public static function getArticles($category_id){
        return self::query()->where('category_id', $category_id)->get();
    }

    public static function getArticle($article_id,$category_id){
        return self::query()->where('article_id', $article_id)->where('category_id', $category_id)->first();
    }

    public static function deleteArticleToCategory($id){
        return self::destroy($id);
    }
}
