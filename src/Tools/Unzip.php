<?php

declare(strict_types=1);

/*
 * This file is part of ezkoding
 *
 * (c) 2025 Oliver Glowa, coding.glowa.com
 *
 * This source file is subject to the Apache-2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace ollily\Tools;

use ZipArchive;

/**
 * Simple helper to unzip a zip file.
 *
 * Example:
 * <code>
 *  // Unzip into the same folder
 *  $result = Unzip.myFile('/download/my.zip');
 *
 *  // Unzip into a different folder
 *  $result = Unzip.myFile('/download/my.zip', '/tmp');
 * </code>
 */
final class Unzip
{
    public const int OK = 0;

    public const int ERROR = 1;

    public const int ZIP_NOT_EXIST = 2;

    public const int TARGET_NOT_EXIST = 3;

    public const int ZIP_NOT_OPENED = 4;

    public const int ZIPARCHIVE_ERROR = 99;

    private function __construct()
    {
        // hide constructor
    }

    /**
     * @param string $zipFile   The zip file including the path
     * @param string $targetDir The folder where to unzip the {@link $zipFile) (Default: the same folder as the zip file)
     *
     * @return int 0=Unzip successfull, else >=1
     */
    final public static function myFile(string $zipFile, string $targetDir = ''): int
    {
        $isSucc = Unzip::ERROR;

        if (!empty($zipFile)) {
            $zipFile = realpath($zipFile);
        }
        if (is_string($zipFile) && file_exists($zipFile) && is_file($zipFile)) {
            if (empty($targetDir)) {
                $targetDir = pathinfo($zipFile, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . pathinfo($zipFile, PATHINFO_FILENAME);
            }
            if (!file_exists($targetDir)) {
                echo sprintf("\nmyFile: Creating folder '%s'!", $targetDir);
                mkdir($targetDir, recursive: true);
            }
            if (file_exists($targetDir) && is_dir($targetDir)) {
                try {
                    $zip = new ZipArchive();
                    $isOpen = $zip->open($zipFile, ZipArchive::RDONLY);
                    if ($isOpen === true) {
                        $zip->extractTo($targetDir);
                        $zip->close();
                        $isSucc = self::OK;
                    } else {
                        echo sprintf("\nmyFile: Zip file '%s' cannot be opened!", $zipFile);
                        $isSucc = self::ZIP_NOT_OPENED;
                    }
                } catch (\Throwable $except) {
                    echo sprintf("\nmyFile: ZipArchive error %s:'%s'", $except->getCode(), $except->getMessage());
                    $isSucc = self::ZIPARCHIVE_ERROR;
                }
            } else {
                echo sprintf("\nmyFile: Target folder '%s' does not exists!", $targetDir);
                $isSucc = self::TARGET_NOT_EXIST;
            }
        } else {
            echo sprintf("\nmyFile: Zip file '%s' does not exists or is not a file!", "$zipFile");
            $isSucc = self::ZIP_NOT_EXIST;
        }

        return $isSucc;
    }
}
