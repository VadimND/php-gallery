<?php

interface CdnUnitInterface
{
    public function isFileExists(string $fileAlias): bool;

    public function sendFileToCdn(string $fileAlias, string $localUrl): bool;

    public function deleteFileFromCdn(string $fileAlias): bool;

    public function getUrlFromCdn(string $fileAlias): string;
}