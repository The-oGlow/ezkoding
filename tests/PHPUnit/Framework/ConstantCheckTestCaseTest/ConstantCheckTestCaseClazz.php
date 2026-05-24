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
    #[\Override]
    public static function setUpBeforeClass(bool $withConstCrossCheck = self::INIT_CROSSCHECK, int $expectedConstsCount = self::INIT_CONST_COUNT): void
    {
        parent::setUpBeforeClass($withConstCrossCheck, $expectedConstsCount);
    }

    #[\Override]
    public static function tearDownAfterClass(): void
    {
        // Deactivate the check, will be called manually in testcase
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    protected static function prepareO2t(): ConstantCheckTestCaseDummyClazz
    {
        return new ConstantCheckTestCaseDummyClazz();
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    protected function getCasto2t(): ConstantCheckTestCaseDummyClazz
    {
        return $this->o2t;
    }

    // Override the visibility for the test cases

    public static function publicCrossCheckConstants(mixed $clazz, array $actualConstants): void
    {
        parent::crossCheckConstants($clazz, $actualConstants);
    }

    public static function publicUpdateActualConsts(?array $checkedConsts): void
    {
        parent::updateActualConsts($checkedConsts);
    }

    public static function publicCheckConstantsCount(int $expectedCount, array $allDefinedConsts): array
    {
        return parent::checkConstantsCount($expectedCount, $allDefinedConsts);
    }

    public function publicVerifyConstAllExists(array $constants = []): void
    {
        parent::verifyConstAllExists($constants);
    }

    public function publicVerifyConstArrayAllExists(array $constants = []): void
    {
        parent::verifyConstArrayAllExists($constants);
    }

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
