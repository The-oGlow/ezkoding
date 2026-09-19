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

use ollily\Tools\Test\TestData as TeDa;
use PHPUnit\Framework\TestCase;

class EnvironmentHelperTest extends TestCase
{
    private const string PHP_VERSION_MIN = '0.0.1';

    private const string PHP_VERSION_CURR = PHP_VERSION;

    private const string PHP_VERSION_MAX = '99.99.999';

    private const string PROJECT_NAME = 'ezkoding';

    public function testHomeDefault(): void
    {
        $actual = EnvironmentHelper::getHome();

        $this->validateActualContains(DIRECTORY_SEPARATOR, $actual);
    }

    public function testHomeUserProfileDirect(): void
    {
        $actual = EnvironmentHelper::getHome(EnvironmentHelper::ENV_HOME_WIN);
        if (empty($actual)) {
            $actual = EnvironmentHelper::getHome(EnvironmentHelper::ENV_HOME_LINUX);
        }

        $this->validateActualContains(DIRECTORY_SEPARATOR, $actual);
    }

    public function testHomeUserProfileIndirect(): void
    {
        $actual = EnvironmentHelper::getHome(TeDa::NOTEXIST_NAME);

        self::assertEquals(TeDa::DATA_EMPTY, $actual);
    }

    public function testGetProjectRoot(): void
    {
        $actual = EnvironmentHelper::getProjectRoot();

        $this->validateActualEnds(self::PROJECT_NAME, $actual);
    }

    public function testGetSystemTemp(): void
    {
        $actual = EnvironmentHelper::getSystemTemp();

        self::assertNotEmpty($actual);
        self::assertFileExists($actual);
    }

    public function testGetSystemTempWithSub(): void
    {
        $expected = TeDa::FILE_FOLDERNAME;

        $actual = EnvironmentHelper::getSystemTemp($expected);

        self::assertNotEmpty($actual);
        $this->validateActualEnds(DIRECTORY_SEPARATOR . $expected, $actual);
    }

    public function testGetComposerFilePath(): void
    {
        $actual = EnvironmentHelper::getComposerFilePath();

        $this->validateActualEnds(self::PROJECT_NAME, $actual);
    }

    public function testGetProjectRootFallback(): void
    {
        $actual = EnvironmentHelper::getProjectRootFallback();

        $this->validateActualEnds(self::PROJECT_NAME, $actual);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerPhpVersion')]
    public function testIsPhpGreater(bool $expected, string $checkVersion): void
    {
        $actual = EnvironmentHelper::isPhpGreater($checkVersion);
        self::assertEquals($expected, $actual);
    }

    /**
     * @param non-empty-string $expected
     * @param string           $actual
     */
    private function validateActualContains(string $expected, string $actual): void
    {
        self::assertNotEmpty($actual);
        self::assertStringContainsString($expected, $actual);
    }

    /**
     * @param non-empty-string $expected
     * @param string           $actual
     */
    private function validateActualEnds(string $expected, string $actual): void
    {
        self::assertNotEmpty($actual);
        self::assertStringEndsWith($expected, $actual);
    }

    // Dataprovider

    /**
     * @return array<mixed>
     */
    public static function providerPhpVersion(): array
    {
        return [
            'equal' => [true, self::PHP_VERSION_CURR],
            'lower' => [true, self::PHP_VERSION_MIN],
            'higher' => [false, self::PHP_VERSION_MAX],
            'wrong' => [true, TeDa::DATA_INVALID],
        ];
    }
}
