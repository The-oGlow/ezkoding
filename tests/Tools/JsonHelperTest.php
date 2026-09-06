<?php

/*
 * Copyright 2026 postm.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace ollily\Tools;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use ollily\Tools\Test\TestData;
use ollily\Tools\Reflection\ClazzHelper;
use Exception;
class JsonHelperTest extends TestCase
{
    
    use EnvironmentVariableTrait;
    
    /**
     * @param array<mixed,mixed> $expected
     * @param string $jsonFile
     */
    #[DataProvider('providerLoadJson')]
    public function testLoadJson(array $expected, string $jsonFile): void
    {
        $actual = JsonHelper::loadJson($jsonFile);
        self::assertEquals($expected, $actual);
    }

    /**
     * @param array $expected
     * @param array<data,data> $data
     * @param string $jsonFile
     */
    #[DataProvider('providerStoreJson')]
    public function testStoreJson(bool $expected, array $data, string $jsonFile): void
    {
        if ($expected) {
            TestData::cleanupTempFile($jsonFile);
        }
        try {
            $actual = JsonHelper::StoreJson($data, $jsonFile);
        } catch (Exception $exc) {
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
        $fileExists = ClazzHelper::getClazzPath(self::class) . DIRECTORY_SEPARATOR . ClazzHelper::getClazzFilename(self::class). TestData::FILE_EXT_JSON;

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
