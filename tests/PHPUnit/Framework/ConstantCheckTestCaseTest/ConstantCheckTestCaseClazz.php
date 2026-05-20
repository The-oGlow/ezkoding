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

namespace PHPUnit\Framework\ConstantCheckTestCaseTest;

use PHPUnit\Framework\ConstantCheckTestCase;

/**
 * This is the test clazz which will be tested.
 *
 * @see  ConstantCheckTestCaseDummyClazz
 * @see  ConstantCheckTestCase
 */
class ConstantCheckTestCaseClazz extends ConstantCheckTestCase // NOSONAR: php:S3360
{
    public static function tearDownAfterClass(): void
    {
        // Deactivate the check, will be called manually in testcase
    }

    /**
     * @return ConstantCheckTestCaseDummyClazz
     */
    protected static function prepareO2t()
    {
        return new ConstantCheckTestCaseDummyClazz();
    }

    /**
     * @return ConstantCheckTestCaseDummyClazz
     */
    protected function getCasto2t()
    {
        return $this->o2t;
    }

    // Override the visibility for the test cases

    /**
     * @param mixed $clazz
     * @param mixed $actualConstants
     */
    public static function publicCrossCheckConstants($clazz, $actualConstants): void
    {
        parent::crossCheckConstants($clazz, $actualConstants);
    }

    /**
     * @param null|array<mixed,mixed> $checkedConsts
     */
    public static function publicUpdateActualConsts($checkedConsts): void
    {
        parent::updateActualConsts($checkedConsts);
    }

    /**
     * @param int                $expectedCount
     * @param array<mixed,mixed> $allDefinedConsts
     *
     * @return array<mixed>
     */
    public static function publicCheckConstantsCount(int $expectedCount, $allDefinedConsts)
    {
        return parent::checkConstantsCount($expectedCount, $allDefinedConsts);
    }

    /**
     * @param array<mixed,mixed> $constants
     */
    public function publicVerifyConstAllExists(array $constants = []): void
    {
        parent::verifyConstAllExists($constants);
    }

    /**
     * @param array<mixed,mixed> $constants
     */
    public function publicVerifyConstArrayAllExists(array $constants = []): void
    {
        parent::verifyConstArrayAllExists($constants);
    }

    /**
     * @param string $constantName
     * @param int    $expectedSize
     */
    public function publicVerifyConstArraySize(string $constantName, int $expectedSize): void
    {
        parent::verifyConstArraySize($constantName, $expectedSize);
    }

    // Test functions

    /**
     * Verify, if the test class has the correct constants.
     */
    public function testConsts(): void
    {
        $consts = self::prepareAllConsts();
        static::updateActualConsts($consts);

        static::verifyConstAllExists($consts);
    }

    // Misc functions

    /**
     * @return array<mixed,mixed>
     */
    public static function prepareAllConsts(): array
    {
        return [
            ConstantCheckTestCaseDummyClazz::TEST_CLAZZ . 'TEST_CLAZZ',
            ConstantCheckTestCaseDummyClazz::TEST_CLAZZ . 'TEST_CONST_PREFIX',
            ConstantCheckTestCaseDummyClazz::TEST_CONST_PREFIX . '_ARRAY',
            ConstantCheckTestCaseDummyClazz::TEST_CONST_PREFIX . '_PUBLIC',
            ConstantCheckTestCaseDummyClazz::TEST_CONST_PREFIX . '_PROTECTED',
            ConstantCheckTestCaseDummyClazz::TEST_CONST_PREFIX . '_PRIVATE',
        ];
    }
}
