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

namespace ollily\Tools\Batch;

use PHPUnit\Framework\TestCase;
use ollily\Tools\Test\TestData;

class BatchTaskHelperTest extends TestCase
{
    /**
     * @param string      $expectedKey
     * @param int         $expectedCount
     * @param bool        $expectedEmpty
     * @param null|string $listKey
     *
     * @dataProvider providerTaskList
     */
    public function testGetTaskList(string $expectedKey, int $expectedCount, bool $expectedEmpty, ?string $listKey = null): void
    {
        $actual = BatchTaskHelper::getTaskList($listKey);

        self::assertInstanceOf(TaskList::class, $actual);
        self::assertEquals($expectedKey, $actual->getListKey());
        self::assertEquals($expectedCount, $actual->count());
        self::assertEquals($expectedEmpty, $actual->isEmpty());
    }

    /**
     * @param string      $expectedKey
     * @param int         $expectedCount
     * @param bool        $expectedEmpty
     * @param string      $fileName
     * @param null|string $listKey
     *
     * @dataProvider providerTaskListFile
     */
    public function testReadTaskList(string $expectedKey, int $expectedCount, bool $expectedEmpty, string $fileName, ?string $listKey = null): void
    {
        $actual = BatchTaskHelper::readTaskList($fileName, $listKey);

        self::assertInstanceOf(TaskList::class, $actual);
        self::assertEquals($expectedKey, $actual->getListKey());
        self::assertEquals($expectedCount, $actual->count());
        self::assertEquals($expectedEmpty, $actual->isEmpty());
    }

    // Dataprovider

    /**
     * @return array<mixed,mixed>
     */
    public function providerTaskList(): array
    {
        return [
          'empty' => [BatchTaskHelper::DEFAULT, 0, true],
        ];
    }

    /**
     * @return array<mixed,mixed>
     */
    public function providerTaskListFile(): array
    {
        return [
            'empty' => [BatchTaskHelper::DEFAULT, 0, true, TestData::FILE_FILENAME_EMPTY],
        ];
    }
}
