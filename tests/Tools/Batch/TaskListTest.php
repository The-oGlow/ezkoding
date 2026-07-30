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
use ollily\Tools\Test\TestData;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\EasyGoingTestCase;

class TaskListTest extends EasyGoingTestCase
{
    public const string LIST_ID = TestData::KEY_ALPHA1;

    public const string DATA = TestData::DATA_ALPHA1;

    public const string DATA_KEY = TestData::KEY_ALPHA1 . TaskList::DEFAULT_ITEM_SEP . TestData::KEY_ALPHA2;

    private string $writeTaskListFile = '';

    #[\Override]
    protected function tearDown(): void
    {
        TestData::cleanupTempFile($this->writeTaskListFile);
    }

    /**
     * @return array<mixed,mixed>
     */
    public static function prepareFiles(): array
    {
        $reflector = new \ReflectionClass(self::class);
        $path = realpath('' . $reflector->getFileName());
        if ($path !== false) {
            $emptyFile = str_replace(TestData::FILE_EXT_PHP, 'Empty' . TestData::FILE_EXT_CSV, $path);
            $existingFile = str_replace(TestData::FILE_EXT_PHP, TestData::FILE_EXT_CSV, $path);
        } else {
            $emptyFile = '';
            $existingFile = '';
        }

        return [TestData::FILE_FILENAME_EMPTY, $emptyFile, $existingFile];
    }

    #[\Override]
    protected static function prepareO2t(): TaskList
    {
        return new TaskList(self::LIST_ID, new BatchConfig(new Map()), true);
    }

    #[\Override]
    protected function getCasto2t(): TaskList
    {
        return $this->o2t;
    }

    public function testGetListId(): void
    {
        $expected = self::LIST_ID;

        $actual = $this->getCasto2t()->getListId();

        self::assertEquals($expected, $actual);
    }

    public function testIsEmpty(): void
    {
        $expected = true;

        $actual = $this->getCasto2t()->isEmpty();

        self::assertEquals($expected, $actual);
    }

    public function testIsWithDataItemId(): void
    {
        $expected = true;

        $actual = $this->getCasto2t()->isWithDataItemId();

        self::assertEquals($expected, $actual);
    }

    public function testAddTaskAndCount(): void
    {
        $expected = $this->randomItems();

        $taskItems = $this->prepareTaskItem($this->getCasto2t()->getListId(), $expected);
        foreach ($taskItems as $taskItem) {
            $this->getCasto2t()->addTask($taskItem);
        }
        self::assertEquals($expected, $this->getCasto2t()->count());
    }

    public function testNextTask(): void
    {
        $listKey = $this->getCasto2t()->getListId();
        $countItems = $this->randomItems();
        $taskItems = $this->prepareTaskItem($listKey, $countItems);
        foreach ($taskItems as $taskItem) {
            $this->getCasto2t()->addTask($taskItem);
        }

        for ($idx = 0; $idx < $countItems; $idx++) {
            $item = $this->getCasto2t()->nextTask();
            self::assertNotNull($item);
            self::assertEquals($listKey . $idx, $item->getItemId());
            self::assertEquals(new Map([self::DATA . $idx, $idx * 10]), $item->getData());
        }

        $item = $this->getCasto2t()->nextTask();
        self::assertNull($item);
    }

    #[DataProvider('providerTaskListFile')]
    public function testReadFileFile(bool $expected, int $expectedCount, string $fileName): void
    {
        $this->o2t = new TaskList(self::class, new BatchConfig(new Map()));
        $actual = $this->getCasto2t()->readFile($fileName);

        self::assertEquals($expectedCount, $this->getCasto2t()->count());
        self::assertEquals($expected, $actual);
    }

    public function testStoreFile(): void
    {
        $expected = true;

        $countItems = $this->randomItems();
        $taskItems = $this->prepareTaskItem($this->getCasto2t()->getListId(), $countItems);
        foreach ($taskItems as $taskItem) {
            $this->getCasto2t()->addTask($taskItem);
        }
        $this->writeTaskListFile = TestData::prepareTempFile();

        $actual = $this->getCasto2t()->storeFile($this->writeTaskListFile);

        self::assertEquals($expected, $actual);
        self::assertFileExists($this->writeTaskListFile);
    }

    public function testParseDataItemIds(): void
    {
        $expected = true;
        $dataKeysLine = self::DATA_KEY;

        $actual = $this->getCasto2t()->parseDataItemIds($dataKeysLine);

        self::assertEquals($expected, $actual);
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
     * @param mixed $listId
     * @param int   $count
     *
     * @return array<mixed,ITaskItem>
     */
    protected function prepareTaskItem(mixed $listId, int $count): array
    {
        $taskItems = [];

        for ($idx = 0; $idx < $count; $idx++) {
            $itemId = "$listId" . $idx;
            $data = new Map([self::DATA . $idx, $idx * 10]);
            $taskItems[] = new TaskItem($itemId, $data);
        }

        return $taskItems;
    }

    protected function randomItems(): int
    {
        return random_int(2, 10);
    }
}
