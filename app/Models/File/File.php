<?php

namespace App\Models\File;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    public static function addFiles($data)
    {
        // 判断是否为文件夹
        if (!empty($data['cdn_path_array'])) {
            foreach ($data['cdn_path_array'] as $cdnPath) {
                $data = array(
                    'bucket' => $data['bucket'],
                    'is_folder' => 1,
                    'original_name' => '',
                    'etag' => '',
                    'cdn_url' => '',
                    'cdn_path' => $cdnPath,
                    'record_version' => 0,
                    'record_operator' => $data['record_operator']
                );
            }
        }
    }

    public static function getFileBySiteId($site_id){
        return self::query()->where('site_id',$site_id)->first();
    }

    public static function saveFile($data)
    {
        $file = new File();
        $file->bucket = $data['bucket'];
        $file->etag = $data['etag'];
        $file->original_name = $data['original_name'];
//        $file->thumb = $data['thumb'];
        $file->cdn_url = $data['cdn_url'];
//        $file->original_image = $data['original_image'];
        $file->original_width = $data['original_width'];
        $file->original_height = $data['original_height'];
//        $file->thumb_width = $data['thumb_width'];
//        $file->thumb_height = $data['thumb_height'];
        $file->record_version = 0;
        $file->record_operator = $data['record_operator'];
        $file->save();
        return $file->id;
    }

    public static function getFileByOriginalName($original_name)
    {
        return self::query()->where('original_name', '=', $original_name)->first();
    }

    public static function getFile($id)
    {
        return self::query()->find($id);
    }

    public static function getFiles()
    {
        return self::query()->orderByDesc('id')->get();
    }
}
