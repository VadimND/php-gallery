<?php
require_once __DIR__ . '/CdnUnitInterface.php';
class AmazonS3CdnService implements CdnUnitInterface
{
    private string $accessKey;
    private string $secretKey;
    private string $bucket;
    private string $region;

    public function __construct(string $accessKey, string $secretKey, string $bucket, string $region)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->bucket = $bucket;
        $this->region = $region;
    }

    public function sendFileToCdn(string $fileAlias, string $localUrl): bool
    {
        echo "Amazon S3: Загружаем $localUrl в $this->bucket как $fileAlias <br/>";
        return true;
    }

    public function deleteFileFromCdn(string $fileAlias): bool
    {
        echo "Amazon S3: Удаляем $fileAlias из $this->bucket <br/>";
        return true;
    }

    public function isFileExists(string $fileAlias): bool
    {
        echo "Amazon S3: Проверяем существование $fileAlias в $this->bucket <br/>";
        return true;
    }

    public function getUrlFromCdn(string $fileAlias): string
    {
        return "https://$this->bucket.s3.$this->region.amazonaws.com/$fileAlias";
    }
}