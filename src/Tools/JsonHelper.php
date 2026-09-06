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

use Ds\Collection;
use Exception;

class JsonHelper
{
    public const string FILE_EXT_JSON = '.json';

    private function __construct()
    {
        // Hide the public constructor
    }

    /**
     * @param string $file
     * @param string $fileExt
     *
     * @return array<mixed,mixed>
     *
     * @throws Exception
     */
    public static function loadJson(string $file, string $fileExt = ''): array
    {
        $data = [];

        $jsonFile = $file;
        if (!empty($fileExt)) {
            $jsonFile .= $fileExt;
        }

        if (file_exists($jsonFile)) {
            $jsonData = file_get_contents($jsonFile);

            if ($jsonData === false) {
                throw new Exception("Cannot read content of: '$jsonFile'");
            }
            $data = json_decode($jsonData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Content has invalid JSON: " . json_last_error_msg());
            }
        } else {
            echo sprintf("File not exists: '%s'\n", $jsonFile);
        }

        return $data;
    }

    /**
     * @param array<mixed,mixed> $data
     * @param string             $file
     * @param string             $fileExt
     * @param bool               $prettyPrint
     *
     * @return bool
     *
     * @throws Exception
     */
    public static function storeJson(array $data, string $file, string $fileExt = '', bool $prettyPrint = false): bool
    {
        $isOk = false;

        $jsonFile = $file;
        if (!empty($fileExt)) {
            $jsonFile .= $fileExt;
        }

        $jsonFolder = dirname($jsonFile);
        if (!is_dir($jsonFolder)) {
            if (!mkdir($jsonFolder, 0o777, true)) {
                throw new Exception("Cannot create folder: '$jsonFolder'");
            }
        }
        if (is_dir($jsonFolder)) {
            if (!file_exists($jsonFile)) {
                $options = $prettyPrint ? JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE : JSON_UNESCAPED_UNICODE;
                $jsonData = json_encode($data, $options);

                if ($jsonData === false) {
                    throw new Exception("Content has invalid JSON: " . json_last_error_msg());
                }

                if (file_put_contents($jsonFile, $jsonData) === false) {
                    throw new Exception("Cannot write content to: '$jsonFile'");
                }
            } else {
                throw new Exception("File already exists: '$jsonFile'");
            }
            $isOk = true;
        }

        return $isOk;
    }

    /**
     * @param Collection<mixed,mixed> $data
     * @param string                  $file
     * @param string                  $fileExt
     * @param bool                    $prettyPrint
     *
     * @return bool
     *
     * @throws Exception
     */
    public static function storeJsonCollection(Collection $data, string $file, string $fileExt = '', bool $prettyPrint = false): bool
    {
        return self::storeJson($data->toArray(), $file, $fileExt, $prettyPrint);
    }
}
