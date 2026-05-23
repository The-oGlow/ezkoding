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

use Monolog\EasyGoingLogger;
use ollily\Tools\Reflection\UnavailableFieldsTrait;
use ollily\Tools\Reflection\UnavailableMethodsTrait;
use ollily\Tools\Test\TestData;
use PHPUnit\Framework\ConstantCheckTestCase;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * This is the test clazz which will test the test clazz.
 *
 * @see ConstantCheckTestCaseClazz
 */
class ConstantCheckTestCaseTest extends TestCase
{
    use UnavailableMethodsTrait;
    use UnavailableFieldsTrait;

    private const TEST_CONST_PREFIX_NAME = 'TEST_CONST_PREFIX';

    private const TEST_CONST_ARRAY_NAME  = 'TEST_CONST_ARRAY';

    private const TEST_CONST_ARRAY_SIZE  = 2;

    private const WRONG_CONST            = 'WRONG_CONST';

    private const WRONG_CONST_SIZE       = 1;

    protected ConstantCheckTestCaseClazz $o2t;

    private static LoggerInterface $logger;

    #[\Override]
    public static function setUpBeforeClass(): void
    {
        self::$logger = EasyGoingLogger::init(ConstantCheckTestCaseTest::class);
        self::$logger->debug('START');

        parent::setUpBeforeClass();
        // function must be called manually
        $sO2t = self::prepareO2t();

        self::$logger->debug('END');
    }

    #[\Override]
    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        // function must be called manually
        $sO2t = self::prepareO2t();
        $sO2t::tearDownAfterClass();
    }

    protected static function prepareO2t(): ConstantCheckTestCaseClazz
    {
        return new ConstantCheckTestCaseClazz(ConstantCheckTestCaseClazz::class);
    }

    protected static function prepareAllConsts(): array
    {
        return ConstantCheckTestCaseClazz::prepareAllConsts();
    }

    #[\Override]
    public function setUp(): void
    {
        self::$logger->debug('START');

        parent::setUp();

        $this->o2t = self::prepareO2t();
        // Must be called manually
        $this->o2t->setUpBeforeClass();
        $this->o2t->setUp();

        self::$logger->debug('END');
    }

    // Test functions

    public function testSetUpBeforeClass(): void
    {
        $sO2t      = self::prepareO2t();
        $clazzName = ConstantCheckTestCase::class;

        $sO2t::setUpBeforeClass();

        $locActualConsts = self::getFieldByReflection($clazzName, 'actualConsts', null);
        self::assertEmpty($locActualConsts);
        $locWithConstCrossCheck = self::getFieldByReflection($clazzName, 'withConstCrossCheck', null);
        self::assertFalse($locWithConstCrossCheck);
        $locExpectedConstsCount = self::getFieldByReflection($clazzName, 'expectedConstsCount', null);
        self::assertEmpty($locExpectedConstsCount);
    }

    public function testTearDownAfterClass(): void
    {
        $sO2t      = self::prepareO2t();
        $clazzName = ConstantCheckTestCase::class;

        $sO2t::tearDownAfterClass();

        $locActualConsts = self::getFieldByReflection($clazzName, 'actualConsts', null);
        self::assertEmpty($locActualConsts);
        $locWithConstCrossCheck = self::getFieldByReflection($clazzName, 'withConstCrossCheck', null);
        self::assertFalse($locWithConstCrossCheck);
        $locExpectedConstsCount = self::getFieldByReflection($clazzName, 'expectedConstsCount', null);
        self::assertEmpty($locExpectedConstsCount);
    }

    public function testTestAllConstants(): void
    {
        try {
            $this->o2t->testAllConstants();
        } catch (\Exception $except) {
            self::fail(sprintf('FAIL: Should not raise any exection: %s', $except->getMessage()));
        }
    }

    public function testTestConsts(): void
    {
        try {
            $this->o2t->testConsts();
        } catch (\Exception $except) {
            self::fail(sprintf('FAIL: Should not raise any exection: %s', $except->getMessage()));
        }
    }

    public function testIsWithConstCrossCheck(): void
    {
        $expected = $this->o2t::INIT_CROSSCHECK;

        $actual = $this->o2t::isWithConstCrossCheck();

        self::assertEquals($expected, $actual);
    }

    /**
     * @param bool               $success
     * @param bool               $crossCheckActive
     * @param string             $clazz
     * @param array<mixed,mixed> $actualConstants
     */
    #[DataProvider('providerCrossCheck')]
    public function testCrossCheckConstants(bool $success, bool $crossCheckActive, string $clazz, array $actualConstants): void
    {
        self::$logger->debug('parameters', [$success, $crossCheckActive, $clazz, $actualConstants]);

        $ccO2t = self::prepareO2t();
        self::setFieldByReflection(get_parent_class($ccO2t), 'withConstCrossCheck', $ccO2t, $crossCheckActive);
        $ccO2t::publicUpdateActualConsts($actualConstants);

        $exception = null;

        self::$logger->info('Testcase', [self::dataName()]);

        try {
            $ccO2t::publicCrossCheckConstants($clazz, $actualConstants);
            self::$logger->info(sprintf("Testcase '%s': %s", self::dataName(), 'success'));
        } catch (\Exception $exception) {
            // Catch the exception
            self::$logger->warning(sprintf("Testcase '%s': %s", self::dataName(), $exception->getMessage()));
        }
        $ccO2t::tearDownAfterClass();
        $this->verifyConstantsTestResult($success, $exception, [$success, $crossCheckActive, $clazz, $actualConstants]);
    }

    public function testUpdateActualConsts(): void
    {
        $this->o2t->setUpBeforeClass(true);

        $checkedConsts = TestData::ARRAY_ALPHA5;
        /** @var array<mixed> */
        $before   = $this->getFieldByReflection(ConstantCheckTestCase::class, 'actualConsts', $this->o2t);

        $expected = count($before) + count($checkedConsts);

        $this->o2t::publicUpdateActualConsts($checkedConsts);

        /** @var array<mixed> */
        $actual = $this->getFieldByReflection(ConstantCheckTestCase::class, 'actualConsts', $this->o2t);

        self::assertCount($expected, $actual);
    }

    public function testCheckConstantsCountDisabled(): void
    {
        $expectedResult   = true;
        $expectedAllCount = 0;

        $expectedCount    = 0;
        $allDefinedConsts = TestData::ARRAY_EMPTY;

        $actual = $this->o2t::publicCheckConstantsCount($expectedCount, $allDefinedConsts);

        self::assertEquals($expectedResult, $actual[0]);
        self::assertEquals($expectedAllCount, $actual[1]);
    }

    /**
     * @param bool               $success
     * @param array<mixed,mixed> $constants
     */
    #[DataProvider('providerConstants')]
    public function testVerifyConstAllExists(bool $success, array $constants): void
    {
        self::$logger->debug('parameters', [$success, $constants]);
        $exception = null;

        self::$logger->info('Testcase', [self::dataName()]);

        try {
            $this->o2t->publicVerifyConstAllExists($constants);
            self::$logger->info(sprintf("Testcase '%s': %s", self::dataName(), 'success'));
        } catch (\Exception $exception) {
            // Catch the exception
            self::$logger->warning(sprintf("Testcase '%s': %s", self::dataName(), $exception->getMessage()));
        }
        $this->verifyConstantsTestResult($success, $exception, [$success, $constants]);
    }

    /**
     * @param bool               $success
     * @param array<mixed,mixed> $constants
     */
    #[DataProvider('providerConstantsArray')]
    public function testVerifyConstArrayAllExists(bool $success, array $constants): void
    {
        self::$logger->debug('parameters', [$success, $constants]);
        $exception = null;

        self::$logger->info('Testcase', [self::dataName()]);

        try {
            $this->o2t->publicVerifyConstArrayAllExists($constants);
            self::$logger->info(sprintf("Testcase '%s': %s", self::dataName(), 'success'));
        } catch (\Exception $exception) {
            // Catch the exception
            self::$logger->warning(sprintf("Testcase '%s': %s", self::dataName(), $exception->getMessage()));
        }
        $this->verifyConstantsTestResult($success, $exception, [$success, $constants]);
    }

    /**
     * @param bool   $success
     * @param string $constantName
     * @param int    $expectedSize
     */
    #[DataProvider('providerConstantName')]
    public function testVerifyConstArraySize(bool $success, string $constantName, int $expectedSize): void
    {
        self::$logger->debug('parameters', [$constantName, $expectedSize]);
        $exception = null;

        self::$logger->info('Testcase', [self::dataName()]);

        try {
            $this->o2t->publicVerifyConstArraySize($constantName, $expectedSize);
            self::$logger->info(sprintf("Testcase '%s': %s", self::dataName(), 'success'));
        } catch (\Exception $exception) {
            // Catch the exception
            self::$logger->warning(sprintf("Testcase '%s': %s", self::dataName(), $exception->getMessage()));
        }
        $this->verifyConstantsTestResult($success, $exception, [$success, $constantName, $expectedSize]);
    }

    // Data Provider

    /**
     * @return array<mixed,mixed>
     */
    public static function providerConstants()
    {
        return [
            'emptyList'     => [true, []],
            'wrongConst'    => [false, [self::WRONG_CONST]],
            'existConstOne' => [true, [self::TEST_CONST_PREFIX_NAME]],
            'existConstAll' => [true, self::prepareAllConsts()],
        ];
    }

    /**
     * @return array<mixed,mixed>
     */
    public static function providerConstantsArray()
    {
        return [
            'emptyList'  => [true, []],
            'wrongConst' => [false, [self::WRONG_CONST => self::TEST_CONST_ARRAY_SIZE]],
            'wrongSize'  => [false, [self::TEST_CONST_ARRAY_NAME => self::WRONG_CONST_SIZE]],
            'allCorrect' => [true, [self::TEST_CONST_ARRAY_NAME => self::TEST_CONST_ARRAY_SIZE]],
        ];
    }

    /**
     * @return array<mixed,mixed>
     */
    public static function providerConstantName()
    {
        return [
            'missingName' => [false, '', self::TEST_CONST_ARRAY_SIZE],
            'wrongConst'  => [false, self::WRONG_CONST, self::TEST_CONST_ARRAY_SIZE],
            'wrongSize'   => [false, self::TEST_CONST_ARRAY_NAME, self::WRONG_CONST_SIZE],
            'allCorrect'  => [true, self::TEST_CONST_ARRAY_NAME, self::TEST_CONST_ARRAY_SIZE],
        ];
    }

    /**
     * @return array<mixed,mixed>
     */
    public static function providerCrossCheck()
    {
        return [
            'emptyListDisabled'    => [true, false, ConstantCheckTestCaseDummyClazz::class, []],
            'emptyListEnabled'     => [false, true, ConstantCheckTestCaseDummyClazz::class, []],
            'wrongConstEnabled'    => [
                false,
                true,
                ConstantCheckTestCaseDummyClazz::class,
                array_merge(self::prepareAllConsts(), [ConstantCheckTestCaseDummyClazz::TEST_CLAZZ . self::WRONG_CONST])
            ],
            'existConstOneEnabled' => [
                false,
                true,
                ConstantCheckTestCaseDummyClazz::class,
                [ConstantCheckTestCaseDummyClazz::TEST_CLAZZ . self::TEST_CONST_PREFIX_NAME]
            ],
            'existConstAllEnabled' => [true, true, ConstantCheckTestCaseDummyClazz::class, self::prepareAllConsts()],
        ];
    }

    // Misc functions

    /**
     * @param bool               $success
     * @param null|\Exception    $exception
     * @param array<mixed,mixed> $extraData
     */
    protected function verifyConstantsTestResult(bool $success, ?\Exception $exception, array $extraData): void
    {
        if ($success) {
            if (empty($exception)) {
                self::$logger->debug('Testcase ended correctly.', $extraData);
            } else {
                self::fail(sprintf('FAIL: Should not raise any exection: %s ', implode(TestData::ARRAY_ITEM_SEP, $extraData)));
            }
        } else {
            if (!empty($exception)) {
                self::$logger->debug('Testcase ended correctly with an exception.', $extraData);
            } else {
                self::fail(sprintf('FAIL: Should raise an exection: %s ', implode(TestData::ARRAY_ITEM_SEP, $extraData)));
            }
        }
        self::assertTrue(true);
    }
}
