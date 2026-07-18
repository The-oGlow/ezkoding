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
use Ds\Set;
use ollily\Tools\Test\TestData;
use PHPUnit\Framework\EasyGoingTestCase;

class TaskItemTest extends EasyGoingTestCase
{

    public const int KEY = TestData::KEY_NUM1;

    /** @var Map<mixed,mixed> */
    public static Map $data;
    public static IItemConfig $config;

    /** @var Map<mixed,mixed> */
    public static Map $configData;

    #[\Override]
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$data = new Map([TestData::DATA_ALPHA1, TestData::DATA_BOOL_T]);
        self::$configData = new Map([TestData::KEY_ALPHA2 => TestData::DATA_ALPHA2]);
        self::$config = new ItemConfig(self::$configData);
    }

    #[\Override]
    protected static function prepareO2t(): ITaskItem
    {
        return new TaskItem(self::KEY, self::$data, self::$config);
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

    public function testGetKey(): void
    {
        $expected = self::KEY;

        $actual = $this->getCasto2t()->getKey();

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

        $actual = $this->getCasto2t()->isDataKeyExist(self::KEY);

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

    public function testGetItemConfig(): void
    {
        $expected = ItemConfig::class;

        $actual = $this->getCasto2t()->getItemConfig();

        self::assertInstanceOf($expected, $actual);
        self::assertEquals(self::$config, $actual);
    }

    public function testGetConfig(): void
    {
        $expectedKey = TestData::KEY_ALPHA2;
        $expectedValue = TestData::DATA_ALPHA2;

        $actual = $this->getCasto2t()->getConfig($expectedKey);

        self::assertEquals($expectedValue, $actual);
    }
}
