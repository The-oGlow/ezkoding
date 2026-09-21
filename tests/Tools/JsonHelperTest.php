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

use Exception;
use ollily\Tools\Reflection\ClazzHelper;
use ollily\Tools\Test\TestData as TeDa;
use PHPUnit\Framework\TestCase;

class JsonHelperTest extends TestCase
{
    /**
     * @param array<mixed> $expected
     * @param string       $jsonFile
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('providerLoadJson')]
    public function testLoadJson(array $expected, string $jsonFile): void
    {
        $actual = JsonHelper::loadJson($jsonFile);
        self::assertEquals($expected, $actual);
    }

    /**
     * @param bool         $expected
     * @param array<mixed> $data
     * @param string       $jsonFile
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('providerStoreJson')]
    public function testStoreJson(bool $expected, array $data, string $jsonFile): void
    {
        if ($expected) {
            TeDa::cleanupTempFile($jsonFile);
        }

        try {
            $actual = JsonHelper::storeJson($data, $jsonFile);
        } catch (Exception $exc) {
            $actual = false;
            if ($expected) {
                self::fail('Must not raise an exception');
            } else {
                self::assertStringContainsString('File already exists', $exc->getMessage());
            }
        }
        self::assertEquals($expected, $actual);

        if ($expected) {
            self::assertFileExists($jsonFile);
        }
        TeDa::cleanupTempFile($jsonFile);
    }

    /**
     * @return array<mixed>
     */
    public static function providerLoadJson(): array
    {
        $fileExists = ClazzHelper::getClazzPath(self::class) . DIRECTORY_SEPARATOR . ClazzHelper::getClazzFilename(self::class) . TeDa::FILE_EXT_JSON;

        $jsonData = ['name' => 'JsonHelperTest', 'data' => [0 => 'data0', 1 => 1]];

        return [
            'fileNotExists' => [[], TeDa::FILE_FILE_NOT_EXISTS],
            'fileExists' => [$jsonData, $fileExists],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function providerStoreJson(): array
    {
        $jsonData = ['name' => basename(self::class), 'data' => [0 => 'data0', 1 => 1]];
        $fileNotExists = EnvironmentHelper::getSystemTemp(TeDa::FILE_FOLDERNAME_NOT_EXIST . DIRECTORY_SEPARATOR . TeDa::FILE_FILENAME_NOT_EXIST . TeDa::FILE_EXT_JSON);
        $fileExists = EnvironmentHelper::getSystemTemp(TeDa::FILE_FOLDERNAME . DIRECTORY_SEPARATOR . TeDa::FILE_FILENAME . TeDa::FILE_EXT_JSON);

        return [
            'fileNotExists' => [true, [$jsonData], $fileNotExists],
            'fileExists' => [true,$jsonData, $fileExists],
        ];
    }
}
