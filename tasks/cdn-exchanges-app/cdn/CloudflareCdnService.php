<?php
require_once __DIR__ . '/CdnUnitInterface.php';
class CloudflareCdnService implements CdnUnitInterface
{
    private string $apiKey;
    private string $zoneId;

    public function __construct(string $apiKey, string $zoneId)
    {
        $this->apiKey = $apiKey;
        $this->zoneId = $zoneId;
    }

    public function isFileExists(string $fileAlias): bool
    {
        echo "Cloudflare: Проверяем существование $fileAlias <br/>";
        return true;
    }

    public function sendFileToCdn(string $fileAlias, string $localUrl): bool
    {
        echo "Cloudflare: Загружаем $localUrl как $fileAlias <br/>";
        return true;
    }

    public function deleteFileFromCdn(string $fileAlias): bool
    {
        echo "Cloudflare: Удаляем $fileAlias <br/>";
        return true;
    }

    public function getUrlFromCdn(string $fileAlias): string
    {
        return "https://cdn.example.com/$fileAlias";
    }
}