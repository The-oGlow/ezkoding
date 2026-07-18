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

use FilesystemIterator;
use ollily\Tools\Test\TestData;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use ZipArchive;

class UnzipTest extends TestCase
{
    use EnvironmentVariableTrait;

    private static string $zipTestFile;

    private static string $targetTestFolder;

    #[\Override]
    public static function tearDownAfterClass(): void
    {
        if (!empty(self::$zipTestFile) && is_file(self::$zipTestFile)) {
            echo sprintf("\nRemoving file '%s'", self::$zipTestFile);
            unlink(self::$zipTestFile);
        }
        self::cleanUpFolder(self::$targetTestFolder);
    }

    public static function cleanUpFolder(string $folder): void
    {
        if (!empty($folder) && is_dir($folder)) {
            $tmpDir = self::getSystemTemp();
            if (str_starts_with($folder, $tmpDir)) {
                echo sprintf("\nRemoving folder '%s'", $folder);
                $recDI = new RecursiveDirectoryIterator($folder, FilesystemIterator::SKIP_DOTS);
                $recII = new RecursiveIteratorIterator($recDI, RecursiveIteratorIterator::CHILD_FIRST);
                /** @var SplFileInfo $deleteItem */
                foreach ($recII as $deleteItem) {
                    $deleteItem->isDir() ? rmdir($deleteItem->getRealPath()) : unlink($deleteItem->getRealPath());
                }
                rmdir($folder);
            }
        }
    }

    public static function prepareZipFile(int $mode = 0): string
    {
        switch ($mode) {
            case 1:
                $fileName = self::getSystemTemp(uniqid()) . '.zip';
                $zip = new ZipArchive();
                if ($zip->open($fileName, ZipArchive::CREATE) === true) {
                    $zip->addFromString(time() . "-sample.txt", "The quick brown fox jumps over the lazy dog.\n");
                    $zip->close();
                }
                break;
            default:
                $fileName = tempnam(self::getSystemTemp(), 'uzm');
                break;
        }
        if (is_string($fileName)) {
            echo sprintf("\nUsing '%s'", $fileName);
        } else {
            $fileName = '';
        }

        return $fileName;
    }

    public static function prepareTargetFolder(): string
    {
        $folder = self::getSystemTemp(uniqid());
        echo sprintf("\nUsing '%s'\n", $folder);

        return $folder;
    }

    /**
     * @param int    $expected
     * @param string $zipFile
     * @param string $targetDir
     */
    #[DataProvider('providerMyFile')]
    public function testMyFile(int $expected, string $zipFile, string $targetDir): void
    {
        $actual = Unzip::myFile($zipFile, $targetDir);

        self::assertEquals($expected, $actual);
    }

    public function testLargeZip(): void
    {
        $sourceDir = self::getHome() . DIRECTORY_SEPARATOR . 'Downloads';
        $zipFile = $sourceDir . DIRECTORY_SEPARATOR . 'sonar-scanner-cli-8.1.0.6389.zip';
        $targetDir = self::prepareTargetFolder();
        if (file_exists($zipFile)) {
            $result = Unzip::myFile($zipFile, $targetDir);
        } else {
            echo "\nLarge zip file test disabled";
            $result = 0;
        }
        self::assertEquals(Unzip::OK, $result);
        if ($result == Unzip::OK) {
            self::cleanUpFolder($targetDir);
        }
    }

    /**
     * @return array<mixed,mixed>
     */
    public static function providerMyFile(): array
    {
        self::$zipTestFile = self::prepareZipFile(1);
        self::$targetTestFolder = self::prepareTargetFolder();

        return[
            'noZipFile' => [Unzip::ZIP_NOT_EXIST, TestData::FILE_FILENAME_EMPTY, TestData::FILE_FOLDERNAME_EMPTY],
            'notAZipFile' => [Unzip::ZIP_NOT_OPENED,self::prepareZipFile(), TestData::FILE_FOLDERNAME_EMPTY],
            'ZipWithDefaultDir' => [Unzip::OK, self::$zipTestFile, TestData::FILE_FOLDERNAME_EMPTY],
            'ZipWithCustomDir' => [Unzip::OK, self::$zipTestFile, self::$targetTestFolder],
        ];
    }
}
