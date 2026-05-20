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

use PHPUnit\Framework\EasyGoingTestCase;
use ollily\Tools\Test\TestData;

class TaskItemTest extends EasyGoingTestCase
{
    public const KEY = TestData::KEY_NUM1;

    public const DATA = [TestData::DATA_ALPHA1, TestData::DATA_BOOL_T];

    /**
     * @return ITaskItem
     */
    protected static function prepareO2t()
    {
        return new TaskItem(self::KEY, self::DATA);
    }

    /**
     * @return ITaskItem
     */
    protected function getCasto2t()
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
        $expected = self::DATA;

        $actual = $this->getCasto2t()->getData();

        self::assertEquals($expected, $actual);
    }
}
