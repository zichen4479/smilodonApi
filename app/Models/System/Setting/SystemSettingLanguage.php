<?php

namespace App\Models\System\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemSettingLanguage extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @param $query
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function filterShow($query)
    {
        $query = self::query()->where('id', 'like', '%' . $query . '%')->orWhere('code', 'like', '%' . $query . '%')->orWhere('title', 'like', '%' . $query . '%')->paginate(config('variable.paginate_limit'));
        return $query;
    }

    /**
     * @param $system_setting_language_id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public static function getSystemSettingLanguage($system_setting_language_id)
    {
        return self::query()->find($system_setting_language_id);
    }

    public static function getSystemSettingLanguages()
    {
        return self::all();
    }
}
