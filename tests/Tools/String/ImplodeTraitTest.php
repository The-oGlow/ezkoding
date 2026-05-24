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

use PHPUnit\Framework\TestCase;
use ollily\Tools\Test\TestData;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * This is the test clazz which will test the test clazz.
 *
 * @see ImplodeTraitTestDummyClazz
 */
class ImplodeTraitTest extends TestCase
{
    public const string ITEM_SEP = '#';

    public const string KEY_SEP = '=>';

    protected ImplodeTraitTestDummyClazz  $o2t;

    #[\Override]
    public function setUp(): void
    {
        parent::setUp();
        $this->o2t = new ImplodeTraitTestDummyClazz();
    }

    public function testImplode_recursiveDefault(): void
    {
        $testData = $this->o2t->traitData;
        $expectedKeyCount = 0;
        $expectedItemCount = count($testData) - 1;

        $actual = $this->o2t::implode_recursive(self::ITEM_SEP, $testData);

        $this->verifyResult($actual, $testData, $expectedKeyCount, $expectedItemCount);
    }

    public function testImplode_recursiveCustom(): void
    {
        $testData = $this->o2t->traitData;
        $expectedKeyCount = count($testData);
        $expectedItemCount = $expectedKeyCount - 1;

        $actual = $this->o2t::implode_recursive(self::ITEM_SEP, $testData, true, true);

        $this->verifyResult($actual, $testData, $expectedKeyCount, $expectedItemCount);
    }

    public function testImplode_recursive_ObjectCustom(): void
    {
        $testData = $this->o2t->traitObject;
        $expectedKeyCount = count($testData) + count($testData[1]);
        $expectedItemCount = $expectedKeyCount - 2;

        $actual = $this->o2t::implode_recursive(self::ITEM_SEP, $testData, true, true);

        $this->verifyResult($actual, $testData, $expectedKeyCount, $expectedItemCount, true);
    }

    /**
     * @param int                $expectedCount
     * @param array<mixed,mixed> $testData
     * @param int                $preserveKeys
     */
    #[DataProvider('providerArrayFlatten')]
    public function testArray_flatten(int $expectedCount, array $testData, int $preserveKeys): void
    {
        $actual = $this->o2t->array_flatten($testData, $preserveKeys);

        self::assertCount($expectedCount, $actual);
    }

    /**
     * @param mixed $actual
     * @param mixed $testData
     * @param int   $expectedKeyCount
     * @param int   $expectedItemCount
     * @param bool  $withClazz
     */
    public function verifyResult(mixed $actual, mixed $testData, int $expectedKeyCount, int $expectedItemCount, bool $withClazz = false): void
    {
        self::assertNotEmpty($actual);
        self::assertEquals($expectedKeyCount, substr_count($actual, self::KEY_SEP));
        self::assertEquals($expectedItemCount, substr_count($actual, self::ITEM_SEP));
        foreach ($testData as $expected) {
            if ($withClazz) {
                if (is_array($expected)) {
                    foreach ($expected as $innerExpected) {
                        self::assertStringContainsString('' . get_class($innerExpected), $actual);
                    }
                } else {
                    self::assertStringContainsString('' . get_class($expected), $actual);
                }
            } else {
                self::assertStringContainsString($expected, $actual);
            }
        }
    }

    // Dataprovider

    /**
     * @return array<mixed,mixed>
     */
    public static function providerArrayFlatten(): array
    {
        return [
            'emptyDefault' => [0, [], 0],
            'noChangeDefault' => [5, TestData::ARRAY_ALPHA5, 0],
            'oneLevelDefault' => [4, [
                    TestData::DATA_ALPHA1,
                    TestData::ARRAY_ALPHA2,
                    TestData::DATA_BOOL_F,
                ], 0,
            ],
            'twoLevelDefault' => [8, [
                    TestData::ARRAY_ALPHA2,
                    [TestData::ARRAY_ALPHA2, TestData::ARRAY_ALPHA2],
                    TestData::ARRAY_ALPHA2,
                ], 0,
            ],
        ];
    }
}
