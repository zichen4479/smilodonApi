<?php

namespace App\Models\Video;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoType extends Model
{
    use HasFactory;

    public static function getVideoTypes()
    {
        return self::all();
    }

    public static function getVideoType($id)
    {
        return self::query()->find($id);
    }
}
