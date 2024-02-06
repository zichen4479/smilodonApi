<?php

namespace App\Models\System\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSettingMenuType extends Model
{
    use HasFactory;
    public static function getMenuType($id){
        return self::query()->find($id);
    }
}
