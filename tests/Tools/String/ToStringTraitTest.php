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

namespace ollily\Tools\String;

use ollily\Tools\Test\TestData;
use ollily\Tools\Test\TestDataFoo;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * This is the test clazz which will test the test clazz.
 *
 * @see ToStringTraitTestDummyClazz
 */
class ToStringTraitTest extends TestCase
{
    use ImplodeTrait;

    public const FORMAT_OBJECT = '{%s Object( [greeting] => %s)}';

    public const FORMAT_OBJECT_IN_OBJECT = '{%s Object( [greeting] => %s Object ( [fooValue:%s:private] => %s ))}';

    public const FORMAT_ARRAY_IN_OBJECT = '{%s Object( [greeting] => Array ( %s ))}';

    public const FORMAT_ARRAY_IN_OBJECT_2 = '{%s Object( [greeting] => Array ( [%s] => %s Object ( [fooValue:%s:private] => %s ) ))}';

    public const FORMAT_ARRAY_KEY_NUM_SEARCH = '/(\w*)=>/';

    public const FORMAT_ARRAY_KEY_ALPHA_SEARCH = "/'([\w\W]+?)'=>/";

    public const FORMAT_ARRAY_ELEM_REPLACE =  '[$1] => ';

    public const FORMAT_ARRAY_SPACE_SEP = ' ';

    public const ILLEGAL_CHARS = [" \n", "\n ", "\n", "  "];

    protected ToStringTraitTestDummyClazz $o2t;

    #[\Override]
    protected function setUp(): void
    {
        $this->markTestIncomplete('FIXME: Ignore 4 a moment');
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
     */
    #[DataProvider('provideDataToString')]
    public function testToString(mixed $data, string $expected): void
    {
        $actualObj = new ToStringTraitTestDummyClazz($data);

        $actual = $actualObj->__toString();

        self::assertEquals($expected, str_replace(self::ILLEGAL_CHARS, '', $actual));
    }

    /**
     * @return array<mixed,mixed>
     *
     * @psalm-suppress InvalidArgument
     */
    public static function provideDataToString(): array
    {
        return [
            'StringAsValue' => [
                TestData::DATA_ALPHA1,
                sprintf(self::FORMAT_OBJECT, ToStringTraitTestDummyClazz::class, TestData::DATA_ALPHA1),
            ],
            'IntegerAsValue' => [
                TestData::DATA_NUM1,
                sprintf(self::FORMAT_OBJECT, ToStringTraitTestDummyClazz::class, TestData::DATA_NUM1),
            ],
            'BoolAsValue' => [
                TestData::DATA_BOOL_T,
                sprintf(self::FORMAT_OBJECT, ToStringTraitTestDummyClazz::class, TestData::DATA_BOOL_T),
            ],
            'ObjectAsValue' => [
                TestData::DATA_OBJECT1(),
                sprintf(
                    self::FORMAT_OBJECT_IN_OBJECT,
                    ToStringTraitTestDummyClazz::class,
                    TestDataFoo::class,
                    TestDataFoo::class,
                    TestData::DATA_NUM1
                ),
            ],
            'ArrayWithNumKey' => [
                TestData::ARRAY_ALPHA3,
                sprintf(
                    self::FORMAT_ARRAY_IN_OBJECT,
                    ToStringTraitTestDummyClazz::class,
                    preg_replace(
                        self::FORMAT_ARRAY_KEY_NUM_SEARCH,
                        self::FORMAT_ARRAY_ELEM_REPLACE,
                        self::implode_recursive(self::FORMAT_ARRAY_SPACE_SEP, TestData::ARRAY_ALPHA3, false, true)
                    )
                ),
            ],
            'ArrayWithAlphaKeys' => [
                TestData::ARRAY_ALPHA_KEY2,
                sprintf(
                    self::FORMAT_ARRAY_IN_OBJECT,
                    ToStringTraitTestDummyClazz::class,
                    preg_replace(
                        self::FORMAT_ARRAY_KEY_ALPHA_SEARCH,
                        self::FORMAT_ARRAY_ELEM_REPLACE,
                        self::implode_recursive(self::FORMAT_ARRAY_SPACE_SEP, TestData::ARRAY_ALPHA_KEY2, false, true)
                    )
                ),
            ],
            'ArrayWithObjectValues' => [
                TestData::ARRAY_OBJECT1(),
                sprintf(
                    self::FORMAT_ARRAY_IN_OBJECT_2,
                    ToStringTraitTestDummyClazz::class,
                    TestData::KEY_ALPHA1,
                    TestDataFoo::class,
                    TestDataFoo::class,
                    TestData::DATA_NUM1
                ),
            ],
        ];
    }
}
