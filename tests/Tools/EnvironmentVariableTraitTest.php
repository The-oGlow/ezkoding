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

use PHPUnit\Framework\TestCase;
use ollily\Tools\Test\TestData;

class EnvironmentVariableTraitTest extends TestCase
{
    use EnvironmentVariableTrait;

    private const PROJECT_NAME = 'ezkoding';

    private const HOME_WIN = 'USERPROFILE';

    private const HOME_LINUX = 'HOME';

    private const HOME_NOTEXIST = 'NOTEXISTS';

    public function testHomeDefault(): void
    {
        $actual = self::getHome();

        $this->validateActualContains($actual, DIRECTORY_SEPARATOR);
    }

//    public function testHomeUserProfileDirect(): void
//    {
//        $actual = self::getHome(self::HOME_WIN);
//        if (empty($actual)) {
//            $actual = self::getHome(self::HOME_LINUX);
//        }
//
//        $this->validateActualContains($actual, DIRECTORY_SEPARATOR);
//    }

//    public function testHomeUserProfileIndirect(): void
//    {
//        $actual = self::getHome(self::HOME_NOTEXIST);
//
//        self::assertEquals(TestData::DATA_EMPTY, $actual);
//    }

//    public function testGetProjectRoot(): void
//    {
//        $actual = self::getProjectRoot();
//
//        $this->validateActualEnds($actual, self::PROJECT_NAME);
//    }

//    public function testGetComposerFilePath(): void
//    {
//        $actual = self::getComposerFilePath();
//
//        $this->validateActualEnds($actual, self::PROJECT_NAME);
//    }
//
//    public function testGetProjectRootFallback(): void
//    {
//        $actual = self::getProjectRootFallback();
//
//        $this->validateActualEnds($actual, self::PROJECT_NAME);
//    }

    private function validateActualContains(string $actual, string $expected): void
    {
        self::assertNotEmpty($actual);
        self::assertStringContainsString($expected, $actual);
    }

    private function validateActualEnds(string $actual, string $expected): void
    {
        var_dump($expected);
        var_dump($actual);
        self::assertNotEmpty($actual);
        self::assertStringEndsWith($expected, $actual);
    }
}
