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

use ollily\Tools\Test\TestData;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class BatchTaskHelperTest extends TestCase
{
    #[DataProvider('providerTaskList')]
    public function testGetTaskList(string $expectedKey, int $expectedCount, bool $expectedEmpty, string $listKey): void
    {
        $actual = BatchTaskHelper::getTaskList($listKey);

        self::assertInstanceOf(TaskList::class, $actual);
        self::assertEquals($expectedKey, $actual->getListKey());
        self::assertEquals($expectedCount, $actual->count());
        self::assertEquals($expectedEmpty, $actual->isEmpty());
    }

    #[DataProvider('providerTaskListFile')]
    public function testReadTaskList(string $expectedKey, int $expectedCount, bool $expectedEmpty, string $fileName, string $listKey): void
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
    public static function providerTaskList(): array
    {
        return [
          'empty' => [BatchTaskHelper::DEFAULT, 0, true, TestData::KEY_EMPTY],
        ];
    }

    /**
     * @return array<mixed,mixed>
     */
    public static function providerTaskListFile(): array
    {
        return [
            'empty' => [BatchTaskHelper::DEFAULT, 0, true, TestData::FILE_FILENAME_EMPTY, TestData::KEY_EMPTY],
        ];
    }
}
