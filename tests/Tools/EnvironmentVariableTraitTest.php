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

use ollily\Tools\Test\TestData;
use PHPUnit\Framework\TestCase;

class EnvironmentVariableTraitTest extends TestCase
{
    use EnvironmentVariableTrait;

    private const string PROJECT_NAME = 'ezkoding';

    private const string HOME_WIN = 'USERPROFILE';

    private const string HOME_LINUX = 'HOME';

    private const string HOME_NOTEXIST = 'NOTEXISTS';

    public function testHomeDefault(): void
    {
        $actual = self::getHome();

        $this->validateActualContains(DIRECTORY_SEPARATOR, $actual);
    }

    public function testHomeUserProfileDirect(): void
    {
        $actual = self::getHome(self::HOME_WIN);
        if (empty($actual)) {
            $actual = self::getHome(self::HOME_LINUX);
        }

        $this->validateActualContains(DIRECTORY_SEPARATOR, $actual);
    }

    public function testHomeUserProfileIndirect(): void
    {
        $actual = self::getHome(self::HOME_NOTEXIST);

        self::assertEquals(TestData::DATA_EMPTY, $actual);
    }

    public function testGetProjectRoot(): void
    {
        $actual = self::getProjectRoot();

        $this->validateActualEnds(self::PROJECT_NAME, $actual);
    }

    public function testGetComposerFilePath(): void
    {
        $actual = self::getComposerFilePath();

        $this->validateActualEnds(self::PROJECT_NAME, $actual);
    }

    public function testGetProjectRootFallback(): void
    {
        $actual = self::getProjectRootFallback();

        $this->validateActualEnds(self::PROJECT_NAME, $actual);
    }

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
}
