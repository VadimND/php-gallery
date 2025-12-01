<?php
require_once __DIR__ . '/cdn/CdnUnitInterface.php';

class CdnProvider
{
    private CdnUnitInterface $cdnService;

    public function __construct(CdnUnitInterface $cdnService)
    {
        $this->cdnService = $cdnService;
    }

    public function isFileExists(string $fileAlias): bool
    {
        return $this->cdnService->isFileExists($fileAlias);
    }

    public function sendFileToCdn(string $fileAlias, string $localUrl): bool
    {
        return $this->cdnService->sendFileToCdn($fileAlias, $localUrl);
    }

    public function deleteFileFromCdn(string $fileAlias): bool
    {
        return $this->cdnService->deleteFileFromCdn($fileAlias);
    }

    public function getUrlFromCdn(string $fileAlias): string
    {
        return $this->cdnService->getUrlFromCdn($fileAlias);
    }
}
