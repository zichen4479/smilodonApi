<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleType extends Model
{
    use HasFactory;

    public static function getArticleTypes()
    {
        return self::all();
    }

    public static function getArticleType($article_type_id)
    {
        return self::query()->find($article_type_id);
    }
}
