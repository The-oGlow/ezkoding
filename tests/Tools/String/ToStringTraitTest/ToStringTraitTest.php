<?php

declare(strict_types=1);

/*
 * This file is part of ezlogging
 *
 * (c) 2025 Oliver Glowa, coding.glowa.com
 *
 * This source file is subject to the Apache-2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace ollily\Tools\String\ToStringTraitTest;

use PHPUnit\Framework\TestCase;
use ollily\Tools\Test\TestData;

/**
 * This is the test clazz which will test the test clazz.
 *
 * @see ToStringTraitTestDummyClazz
 */
class ToStringTraitTest extends TestCase
{
    public const FORMAT_ARRAY = '%s:[%s]';

    public const FORMAT_ITEMS = '%s:{%s}';

    public const FORMAT_NUM = '%s:%s';

    public const FORMAT_ALPHA = '%s:\'%s\'';

    /** @var ToStringTraitTestDummyClazz */
    protected $o2t;

    protected function setUp(): void
    {
        TestData::ARRAY_OBJECT1();
        parent::setUp();
        $this->o2t = new ToStringTraitTestDummyClazz();
    }

    public function testToStringDefault(): void
    {
        $result = $this->o2t->__toString();
        self::assertNotEmpty($result);
        self::assertStringContainsString(get_class($this->o2t), $result);
    }

    public function testWakeup(): void
    {
        self::expectException(\BadMethodCallException::class);
        $this->o2t->__wakeup();
    }

    /**
     * @param mixed  $data
     * @param string $expected
     *
     * @dataProvider provideDataToString
     */
    public function testToString($data, string $expected): void
    {
        $actualObj = new ToStringTraitTestDummyClazz($data);
        $actual = $actualObj->__toString();
        self::assertEquals($expected, $actual);
    }

    /**
     * @return array<mixed,mixed>
     *
     * @psalm-suppress InvalidArgument
     */
    public function provideDataToString(): array
    {
        return [
            'StringAsValue' => [
                TestData::DATA_ALPHA1,
                sprintf(self::FORMAT_ALPHA, ToStringTraitTestDummyClazz::class, TestData::DATA_ALPHA1)
            ],
            'IntegerAsValue' => [
                TestData::DATA_NUM1,
                sprintf(self::FORMAT_NUM, ToStringTraitTestDummyClazz::class, TestData::DATA_NUM1),
            ],
            'BoolAsValue' => [
                TestData::DATA_BOOL_T,
                sprintf(self::FORMAT_NUM, ToStringTraitTestDummyClazz::class, TestData::DATA_BOOL_T),
            ],
            'ObjectAsValue' => [
                TestData::DATA_OBJECT1(),
                sprintf(self::FORMAT_NUM, ToStringTraitTestDummyClazz::class, TestData::DATA_OBJECT1()),
            ],
            'ArrayWithNumKey' => [
                TestData::ARRAY_ALPHA3,
                sprintf(self::FORMAT_ARRAY, ToStringTraitTestDummyClazz::class, implode(TestData::ARRAY_ITEM_SEP, TestData::ARRAY_ALPHA3)),
            ],
            'ArrayWithAlphaKeys' => [
                TestData::ARRAY_ALPHA_KEY2,
                sprintf(self::FORMAT_ARRAY, ToStringTraitTestDummyClazz::class, implode(TestData::ARRAY_ITEM_SEP, TestData::ARRAY_ALPHA_KEY2)),
            ],
            'ArrayWithObjectValues' => [
                TestData::ARRAY_OBJECT2(),
                sprintf(self::FORMAT_ARRAY, ToStringTraitTestDummyClazz::class, implode(TestData::ARRAY_ITEM_SEP, TestData::ARRAY_OBJECT2())),
            ]
        ];
    }
}
