<?php

namespace App\Http\Controllers\File;

set_time_limit(0);

use App\Exceptions\ESUException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use App\Http\Requests\addBigFileRequest;
use App\Http\Requests\addFileRequest;
use App\Models\File\File;
use App\Services\S3ClientService;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileController extends ESUBaseController
{
    protected $client;
    protected $bucketName;

    public function __construct()
    {
        // 判断是否有Bucket
        $this->client = new S3ClientService();
        $this->bucketName = 'smilodon';
    }

    /**
     * @param $id
     * @return mixed|void
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function showFile($id)
    {
        $file = File::getFile($id);
        if (empty($file)) {
            $code = "2222";
            $msg = "文件不存在";
            return $this->error($msg, $code);
        }
        $data = array(
            'id' => $file->id,
            'cdn_url' => config('variable.image_domain').$file->cdn_url,
        );
        $code = 1;
        $msg = "获取成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param  Request  $request
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function addFile(addFileRequest $request)
    {
//        if ($this->client->client()->doesBucketExist($this->bucketName) == false) {
//            $this->client->client()->createBucket([
//                'Bucket' => $this->bucketName
//            ]);
//        }
        $files = $request->file('files');
        DB::beginTransaction();
        try {
            $filesSuccess = array();
            foreach ($files as $file) {
                // 文件类型
                $mineType = $file->getClientMimeType();
                $fileMineType = explode("/", $mineType);
                $snowFlake = new \Godruoyi\Snowflake\Snowflake;
                $snowFlakeId = $snowFlake->id();
                $fileExtension = $file->getClientOriginalExtension();
                $fileName = $file->getClientOriginalName();
                $originalName = pathinfo($fileName, PATHINFO_FILENAME).".".$fileExtension;
                $cdnKey = $snowFlakeId.".".$fileExtension;
//                $filePath = base_path() . '/storage/app/public/files/';
//                $waterMark = base_path() . '/resources/images/logo.png';
                // 文件真实名字SLUG
                $body = $file->getContent();
                Storage::disk('public')->put('files/'.$cdnKey, $body);

                if (end($fileMineType) == "jpg" || end($fileMineType) == "png" || end($fileMineType) == "gif" || end($fileMineType) == "jpeg") {
                    $imageSize = getimagesize($file);
                    $imageWidth = $imageSize[0];
                    $imageHeight = $imageSize[1];
//                    $scale = $imageWidth / $imageHeight;
//                    if ($imageWidth <= 1000) {
//                        $scale = $scale * 0.5;
//                        $waterMark = base_path() . '/resources/images/logo.png';
//                    } else {
//                        $scale = $scale * 0.25;
//                        $waterMark = base_path() . '/resources/images/logo_big.png';
//                    }
//                    $image = Image::make($filePath . $cdnKey);
//                    $imageThumb = Image::make($filePath . $cdnKey)->resize((int)($imageWidth * $scale), (int)($imageHeight * $scale));
//                    $imageThumb->save($filePath . $snowFlakeId . "_thumb." . $fileExtension);
//                    $image->insert($waterMark, 'bottom-center', 0, 25);
//                    $image->save($filePath . $snowFlakeId . "_water_mark." . $fileExtension);
//                    $waterMarkImageUrl = $snowFlakeId . "_water_mark." . $fileExtension;
//                    $thumbUrl = $snowFlakeId . "_thumb." . $fileExtension;
//                    $thumbSize = getimagesize($filePath . $snowFlakeId . "_thumb." . $fileExtension);
//                    $thumbWidth = $thumbSize[0];
//                    $thumbHeight = $thumbSize[1];
                } else {
                    $imageWidth = 0;
                    $imageHeight = 0;
//                    $thumbWidth = 0;
//                    $thumbHeight = 0;
//                    $thumbUrl = '';
//                    $waterMarkImageUrl = $snowFlakeId .".". $fileExtension;
                }

                try {
//                    $objectData = [
//                        'Bucket' => $this->bucketName,
//                        'ContentType' => $mineType,
//                        'Key' => $cdnKey,
//                        'Body' => $body,
//                        'ACL' => 'public-read',
//                        'CacheControl' => 'max-age=31536000'
//                    ];
//                    $result = $this->client->client()->putObject($objectData);
                    $dataFile = array(
//                        'bucket' => $this->bucketName,
                        'bucket' => '',
                        'original_name' => $originalName,
//                        'etag' => str_replace('"', '', $result['ETag']),
                        'etag' => '',
//                        'cdn_url' => $result['ObjectURL'],
                        'cdn_url' => $cdnKey,
                        'full_cdn_url' => config('variable.image_domain').$cdnKey,
//                        'thumb' => $thumbUrl,
//                        'original_image' => $cdnKey,
                        'original_width' => $imageWidth,
                        'original_height' => $imageHeight,
//                        'thumb_width' => $thumbWidth,
//                        'thumb_height' => $thumbHeight,
                        'record_version' => 0,
//                        'record_operator' => $this->bucketName
                        'record_operator' => ''
                    );
                    $fileId = File::saveFile($dataFile);
                    $filesSuccess[] = File::getFile($fileId);
                } catch (S3Exception $e) {
                    return $e->getMessage();
                }
            }
            DB::commit();
        } catch (ESUException $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            $msg = trans('merchant-file.error_add');
            $code = 20001;
            return $this->error($msg, $code);
        }
        $data = array();
        foreach ($filesSuccess as $filesSuccess) {
            $filesSuccess['full_cdn_url'] = config('variable.image_domain').$filesSuccess['cdn_url'];
            $data[] = $filesSuccess;
        }
        $code = 1;
        $msg = "success";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param  addBigFileRequest  $request
     * @return mixed|string|void
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function addBigFile(addBigFileRequest $request)
    {
        if ($this->client->client()->doesBucketExist($this->bucketName) == false) {
            $this->client->client()->createBucket([
                'Bucket' => $this->bucketName
            ]);
        }
        Log::info($request->all());
        die;
//        $file = $request->file('file');
        DB::beginTransaction();
        try {
            // 文件名
            echo $fileName = $file->getClientOriginalName();
            die;
            $fileExtension = $file->getClientOriginalExtension();
            // 文件类型
            $mineType = $file->getClientMimeType();
            // 文件真实名字SLUG
            $originalName = pathinfo($imageName, PATHINFO_FILENAME).".".$imageExtension;
            $img = Image::make($file);
            $body = $img->stream()->detach();
            $snowFlake = new \Godruoyi\Snowflake\Snowflake;
            $snowFlakeId = $snowFlake->id();
            $cdnKey = $snowFlakeId.".".$imageExtension;
            Storage::disk('public')->put('images/'.$cdnKey, $body);
            try {
                $objectData = [
                    'Bucket' => $this->bucketName,
                    'ContentType' => $mineType,
                    'Key' => $cdnKey,
                    'Body' => $body,
                    'ACL' => 'public-read',
                    'CacheControl' => 'max-age=31536000'
                ];
                $result = $this->client->client()->putObject($objectData);
                $dataFile = array(
                    'bucket' => $this->bucketName,
                    'original_name' => $originalName,
                    'etag' => str_replace('"', '', $result['ETag']),
                    'cdn_url' => $result['ObjectURL'],
                    'width' => $imageWidth,
                    'height' => $imageHeight,
                    'record_version' => 0,
                    'record_operator' => $this->bucketName
                );
                $fileId = File::saveFile($dataFile);
                $filesSuccess[] = File::getFile($fileId);
            } catch (S3Exception $e) {
                return $e->getMessage();
            }
            DB::commit();
        } catch (ESUException $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            $msg = trans('merchant-file.error_add');
            $code = 20001;
            return $this->error($msg, $code);
        }
        $data = $filesSuccess;
        $code = 1;
        $msg = "success";
        return $this->response($code, $data, $msg);
    }

    public function listFile(Request $request)
    {
        $files = File::getFiles()->toArray();
//        foreach ($files as $file) {
//            $filePath = base_path() . '/storage/app/public/files/';
//            $originalImagePath = $filePath . $file['original_image'];
//            $imageSize = getimagesize($originalImagePath);
//            $imageWidth = $imageSize[0];
//            $imageHeight = $imageSize[1];
//            $scale = $imageWidth / $imageHeight;
//            if ($imageWidth <= 1000) {
//                $scale = $scale * 0.5;
//                $waterMark = base_path() . '/resources/images/logo.png';
//            } else {
//                $scale = $scale * 0.25;
//                $waterMark = base_path() . '/resources/images/logo_big.png';
//            }
//            $image = Image::make($originalImagePath);
//            $fileName = $image->filename;
//            $fileExtension = $image->extension;
//
//            $imageThumb = Image::make($originalImagePath)->resize((int)($imageWidth * $scale), (int)($imageHeight * $scale));
//            $imageThumb->save($filePath . $fileName . "_thumb." . $fileExtension);
//            $image->insert($waterMark, 'bottom-center', 0, 25);
//            $image->save($filePath . $fileName . "_water_mark." . $fileExtension);
//            $waterMarkImageUrl = $fileName . "_water_mark." . $fileExtension;
//            $thumbUrl = $fileName . "_thumb." . $fileExtension;
//            $thumbSize = getimagesize($filePath . $fileName . "_thumb." . $fileExtension);
//            $thumbWidth = $thumbSize[0];
//            $thumbHeight = $thumbSize[1];
//            File::query()->where('id',$file['id'])->update([
//                'thumb' => $thumbUrl,
//                'cdn_url' => $waterMarkImageUrl,
//                'thumb_width' => $thumbWidth,
//                'thumb_height' => $thumbHeight
//            ]);
//        }
//        die;
        $data = $this->pagination($request, $files);
        $code = 1;
        $msg = "获取成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param $id
     * @return mixed|void
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function deleteFile($id)
    {
        // 需要验证有没有被使用，正在被使用中的图片不允许被删除
        DB::beginTransaction();
        try {
            File::destroy($id);
            DB::commit();
        } catch (ESUException $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            $msg = trans('file.error_delete');
            $code = 20001;
            return $this->error($msg, $code);
        }
        $code = 1;
        $msg = "删除成功";
        $data = null;
        return $this->response($code, $data, $msg);
    }
}
