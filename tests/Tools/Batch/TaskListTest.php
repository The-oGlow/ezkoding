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
use PHPUnit\Framework\EasyGoingTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class TaskListTest extends EasyGoingTestCase
{
    /** @var string */
    public const KEY = TestData::KEY_ALPHA1;

    /** @var string */
    public const DATA = TestData::DATA_ALPHA1;

    /** @var string */
    private $writeTaskListFile = '';

    protected function tearDown(): void
    {
        TestData::cleanupTempFile($this->writeTaskListFile);
    }

    public static function prepareFiles(): array
    {
        $reflector = new \ReflectionClass(self::class);
        $path = '' . realpath('' . $reflector->getFileName());
        $emptyFile = str_replace(TestData::FILE_EXT_PHP, 'Empty' . TestData::FILE_EXT_CSV, $path);
        $existingFile = str_replace(TestData::FILE_EXT_PHP, TestData::FILE_EXT_CSV, $path);

        return [TestData::FILE_FILENAME_EMPTY,$emptyFile, $existingFile];
    }

    /**
     * @return TaskList
     */
    protected static function prepareO2t()
    {
        return new TaskList(self::KEY);
    }

    /**
     * @return TaskList
     */
    protected function getCasto2t()
    {
        return $this->o2t;
    }

    public function testGetListKey(): void
    {
        $expected = self::KEY;

        $actual = $this->getCasto2t()->getListKey();

        self::assertEquals($expected, $actual);
    }

    public function testIsEmpty(): void
    {
        $expected = true;

        $actual = $this->getCasto2t()->isEmpty();

        self::assertEquals($expected, $actual);
    }

    public function testAddTask(): void
    {
        $expected = $this->randomItems();

        foreach ($this->prepareTaskItem($this->getCasto2t()->getListKey(), $expected) as $taskItem) {
            $this->getCasto2t()->addTask($taskItem);
        }
        self::assertEquals($expected, $this->getCasto2t()->count());
    }

    public function testCount(): void
    {
        $expected = $this->randomItems();

        foreach ($this->prepareTaskItem($this->getCasto2t()->getListKey(), $expected) as $taskItem) {
            $this->getCasto2t()->addTask($taskItem);
        }
        self::assertEquals($expected, $this->getCasto2t()->count());
    }

    public function testNextTask(): void
    {
        $listKey = $this->getCasto2t()->getListKey();
        $countItems = $this->randomItems();
        foreach ($this->prepareTaskItem($listKey, $countItems) as $taskItem) {
            $this->getCasto2t()->addTask($taskItem);
        }

        for ($idx = 0; $idx < $countItems; $idx++) {
            $item = $this->getCasto2t()->nextTask();
            self::assertNotNull($item);
            self::assertEquals($listKey . $idx, $item->getKey());
            self::assertEquals([self::DATA . $idx, $idx * 10], $item->getData());
        }

        $item = $this->getCasto2t()->nextTask();
        self::assertNull($item);
    }

    /**
     * @param bool   $expected
     * @param string $fileName
     */
    #[DataProvider('providerTaskListFile')]
    public function testReadFileFile(bool $expected, int $expectedCount, ?string $fileName): void
    {
        $this->o2t = new TaskList(self::class);
        $actual = $this->getCasto2t()->readFile($fileName);

        self::assertEquals($expectedCount, $this->getCasto2t()->count());
        self::assertEquals($expected, $actual);
    }

    public function testStoreFile(): void
    {
        $expected = true;

        $countItems = $this->randomItems();
        foreach ($this->prepareTaskItem($this->getCasto2t()->getListKey(), $countItems) as $taskItem) {
            $this->getCasto2t()->addTask($taskItem);
        }
        $this->writeTaskListFile = TestData::prepareTempFile();

        $actual = $this->getCasto2t()->storeFile($this->writeTaskListFile);

        self::assertEquals($expected, $actual);
        self::assertFileExists($this->writeTaskListFile);
    }

    // Dataprovider

    /**
     * @return array<string,mixed>
     */
    public static function providerTaskListFile(): array
    {
        return [
            'emptyFileName' => [false, 0, self::prepareFiles()[0]],
            'emptyFile' => [true, 0, self::prepareFiles()[1]],
            'existingFile' => [true, 3, self::prepareFiles()[2]],
        ];
    }

    // Misc functions

    /**
     * @param mixed $taskListKey
     * @param int   $count
     *
     * @return array<ITaskItem>
     */
    protected function prepareTaskItem($taskListKey, int $count): array
    {
        $items = [];

        for ($idx = 0; $idx < $count; $idx++) {
            $items[] = new TaskItem("$taskListKey" . $idx, [self::DATA . $idx, $idx * 10]);
        }

        return $items;
    }

    protected function randomItems(): int
    {
        return random_int(2, 10);
    }
}
