<?php
require_once __DIR__ . '/cdn/AmazonS3CdnService.php';
require_once __DIR__ . '/cdn/CloudflareCdnService.php';
require_once __DIR__ . '/CdnProvider.php';

// Инициализируем объекты для соединения и операций с CDN
$cloudCdn = new CloudflareCdnService('12345abc', 'any_zone_id');
$amazonCdn = new AmazonS3CdnService('access_key', 'secret_key', 'bucket', 'region');
$cloudflareProvider = new CdnProvider($cloudCdn);
$amazonProvider = new CdnProvider($amazonCdn);

$fileAlias = 'file123';
$localUrl = '/path/to/file.txt';

// Тестируем Cloudflare CDN
if ($cloudflareProvider->isFileExists($fileAlias)) {
    $cloudflareProvider->deleteFileFromCdn($fileAlias);
}

$cloudflareProvider->sendFileToCdn($fileAlias, $localUrl);

echo "Файл доступен по ссылке: " . $cloudflareProvider->getUrlFromCdn($fileAlias) . '<br/>';

// Тестируем Amazon CDN
if ($amazonProvider->isFileExists($fileAlias)) {
    $amazonProvider->deleteFileFromCdn($fileAlias);
}

$amazonProvider->sendFileToCdn($fileAlias, $localUrl);

echo "Файл доступен по ссылке: " . $amazonProvider->getUrlFromCdn($fileAlias);

/*
Вывод:

Cloudflare: Проверяем существование file123
Cloudflare: Удаляем file123
Cloudflare: Загружаем /path/to/file.txt как file123
Файл доступен по ссылке: https://cdn.example.com/file123
Amazon S3: Проверяем существование file123 в bucket
Amazon S3: Удаляем file123 из bucket
Amazon S3: Загружаем /path/to/file.txt в bucket как file123
Файл доступен по ссылке: https://bucket.s3.region.amazonaws.com/file123
*/