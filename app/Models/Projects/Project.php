<?php

namespace App\Models\Projects;

use App\Models\Site\Site;
use App\Models\System\Setting\SystemSettingLanguage;
use App\Models\System\Setting\SystemSettingMenuType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'title',
        'content'
    ];

    public static function addProject($data)
    {
        $ads = new Project();
        $ads->title = $data['title'];
        $ads->content = json_encode($data['content']);
        $ads->save();
        return $ads->id;
    }

    public static function editProject($id, $data)
    {
        $site = Site::getSite($data['site_id']);
        self::query()->find($id)->update([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);
    }

    public static function getProject($id)
    {
        return self::query()->find($id);
    }

    public static function getProjects()
    {

        return self::query()->orderBy('id', 'desc')->paginate(20);

    }

    public static function deleteProject($id)
    {
        self::destroy($id);
    }
}
