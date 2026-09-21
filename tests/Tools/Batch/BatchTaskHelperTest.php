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

use Ds\Map;
use ollily\Tools\Test\TestData as TeDa;
use PHPUnit\Framework\TestCase;

class BatchTaskHelperTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('providerTaskList')]
    public function testGetTaskList(string $expectedKey, int $expectedCount, bool $expectedEmpty, string $listKey): void
    {
        $actual = BatchTaskHelper::getTaskList($listKey);

        self::assertInstanceOf(TaskList::class, $actual);
        self::assertEquals($expectedKey, $actual->getListId());
        self::assertEquals($expectedCount, $actual->count());
        self::assertEquals($expectedEmpty, $actual->isEmpty());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerTaskListFile')]
    public function testReadTaskList(string $expectedKey, int $expectedCount, bool $expectedEmpty, string $fileName, IBatchConfig $itemConfig, string $listKey): void
    {
        $actual = BatchTaskHelper::readTaskList($fileName, $itemConfig, $listKey);

        self::assertInstanceOf(TaskList::class, $actual);
        self::assertEquals($expectedKey, $actual->getListId());
        self::assertEquals($expectedCount, $actual->count());
        self::assertEquals($expectedEmpty, $actual->isEmpty());
    }

    // Dataprovider

    /**
     * @return array<mixed>
     */
    public static function providerTaskList(): array
    {
        return [
          'empty' => [BatchTaskHelper::DEFAULT, 0, true, TeDa::KEY_EMPTY],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function providerTaskListFile(): array
    {
        return [
            'empty' => [BatchTaskHelper::DEFAULT, 0, true, TeDa::FILE_FILENAME_EMPTY, new BatchConfig(new Map()), TeDa::KEY_EMPTY],
            'simpleFile' => [BatchTaskHelper::DEFAULT, 3, false, TaskListTest::prepareFiles()[2], new BatchConfig(new Map()), TeDa::KEY_EMPTY],
        ];
    }
}
