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
use PHPUnit\Framework\EasyGoingTestCase;

class TaskItemTest extends EasyGoingTestCase
{
    public const int LIST_ID = TestData::KEY_NUM1;

    /** @var Map<mixed,mixed>
     * @param-var Map<TDataKey,TDataValue> */
    public static Map $data;


    #[\Override]
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$data = new Map([TestData::DATA_ALPHA1, TestData::DATA_BOOL_T]);
    }

    #[\Override]
    protected static function prepareO2t(): ITaskItem
    {
        return new TaskItem(self::LIST_ID, self::$data);
    }

    #[\Override]
    protected function getCasto2t(): ITaskItem
    {
        return $this->o2t;
    }

    public function testInstance(): void
    {
        $expected = ITaskItem::class;

        $actual = $this->getCasto2t();

        self::assertInstanceOf($expected, $actual);
    }

    public function testGetItemId(): void
    {
        $expected = self::LIST_ID;

        $actual = $this->getCasto2t()->getItemId();

        self::assertEquals($expected, $actual);
    }

    public function testGetData(): void
    {
        $expected = self::$data;

        $actual = $this->getCasto2t()->getData();

        self::assertEquals($expected, $actual);
    }

    public function testGetDataKeys(): void
    {
        $expected = self::$data->keys();

        $actual = $this->getCasto2t()->getDataKeys();

        self::assertEquals($expected, $actual);
    }

    public function testGetDataValueNotExists(): void
    {
        $expected = TestData::KEY_EMPTY;

        $actual = $this->getCasto2t()->getDataValue(TestData::KEY_EMPTY);

        self::assertEquals($expected, $actual);
    }

    public function testGetDataValue(): void
    {
        $expected = TestData::DATA_BOOL_T;

        $actual = $this->getCasto2t()->getDataValue(TestData::KEY_NUM1);

        self::assertEquals($expected, $actual);
    }

    public function testIsDataKeyExist(): void
    {
        $expected = true;

        $actual = $this->getCasto2t()->isDataKeyExist(self::LIST_ID);

        self::assertEquals($expected, $actual);
    }

    public function testEmpty(): void
    {
        $expected = false;

        $actual = $this->getCasto2t()->empty();

        self::assertEquals($expected, $actual);
    }

    public function testCount(): void
    {
        $expected = count(self::$data);

        $actual = $this->getCasto2t()->count();

        self::assertEquals($expected, $actual);
    }
}
