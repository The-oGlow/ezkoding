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
use ollily\Tools\Test\TestData;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class JsonHelperTest extends TestCase
{
    use EnvironmentVariableTrait;

    /**
     * @param array<mixed,mixed> $expected
     * @param string             $jsonFile
     */
    #[DataProvider('providerLoadJson')]
    public function testLoadJson(array $expected, string $jsonFile): void
    {
        $actual = JsonHelper::loadJson($jsonFile);
        self::assertEquals($expected, $actual);
    }

    /**
     * @param bool               $expected
     * @param array<mixed,mixed> $data
     * @param string             $jsonFile
     */
    #[DataProvider('providerStoreJson')]
    public function testStoreJson(bool $expected, array $data, string $jsonFile): void
    {
        if ($expected) {
            TestData::cleanupTempFile($jsonFile);
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
        TestData::cleanupTempFile($jsonFile);
    }

    /**
     * @return array<mixed,mixed>
     */
    public static function providerLoadJson(): array
    {
        $fileExists = ClazzHelper::getClazzPath(self::class) . DIRECTORY_SEPARATOR . ClazzHelper::getClazzFilename(self::class) . TestData::FILE_EXT_JSON;

        $jsonData = ['name' => basename(self::class), 'data' => [0 => 'data0', 1 => 1]];

        return [
            'fileNotExists' => [[], TestData::FILE_FILE_NOT_EXISTS],
            'fileExists' => [$jsonData, $fileExists],
        ];
    }

    /**
     * @return array<mixed,mixed>
     */
    public static function providerStoreJson(): array
    {
        $jsonData = ['name' => basename(self::class), 'data' => [0 => 'data0', 1 => 1]];
        $fileNotExists = self::getSystemTemp(TestData::FILE_FOLDERNAME_NOT_EXIST . DIRECTORY_SEPARATOR . TestData::FILE_FILENAME_NOT_EXIST . TestData::FILE_EXT_JSON);
        $fileExists = self::getSystemTemp(TestData::FILE_FOLDERNAME . DIRECTORY_SEPARATOR . TestData::FILE_FILENAME . TestData::FILE_EXT_JSON);

        return [
            'fileNotExists' => [true, [$jsonData], $fileNotExists],
            'fileExists' => [true,$jsonData, $fileExists],
        ];
    }
}
