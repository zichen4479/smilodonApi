<?php


namespace App\Services;


use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use App\Contracts\S3ClientContract;

use Illuminate\Support\Facades\Log;

class S3ClientService
{

    /**
     * @return string
     */
    public function client()
    {
        try {
            $client = new S3Client([
                'version' => 'latest',
                'region' => 'us-east-1',
                'endpoint' => 'https://nyc3.digitaloceanspaces.com',
                'credentials' => [
                    'key' => config('variable.s3_spaces_key'),
                    'secret' => config('variable.s3_spaces_secret'),
                ],
                'visibility' => 'public-read',
                'options' => ['CacheControl' => 'max-age=315360000, no-transform, public']
            ]);
            return $client;
        } catch (S3Exception $exception) {
            Log::error($exception->getMessage().$exception->getFile());
            return $exception->getMessage();
        }
    }
}
