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

namespace PHPUnit\Framework\EasyGoingTestCaseTest;

use Monolog\ConsoleLogger;
use ollily\Tools\Reflection\UnavailableFieldsTrait;
use ollily\Tools\Reflection\UnavailableMethodsTrait;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * This is the test clazz which will test the test clazz.
 *
 * @see EasyGoingTestCaseClazz
 */
class EasyGoingTestCaseTest extends TestCase
{
    use UnavailableMethodsTrait;
    use UnavailableFieldsTrait;

    /** @var EasyGoingTestCaseClazz */
    protected $o2t;

    /** @var LoggerInterface */
    private static $logger;

    /**
     * @return EasyGoingTestCaseClazz
     */
    protected static function prepareO2t()
    {
        return new EasyGoingTestCaseClazz();
    }

    /**
     * @return array<mixed,mixed>
     */
    protected static function prepareAllConsts(): array
    {
        return EasyGoingTestCaseClazz::prepareAllConsts();
    }

    /**
     * @param mixed              $name
     * @param array<mixed,mixed> $data
     * @param string             $dataName
     */
    public function __construct($name = null, $data = [], $dataName = '')
    {
        self::$logger = new ConsoleLogger(EasyGoingTestCaseTest::class);
        self::$logger->debug('START');
        parent::__construct($name, $data, $dataName);
        self::$logger->debug('END');
    }

    public function setUp(): void
    {
        self::$logger->debug('START');
        parent::setUp();
        $this->o2t = self::prepareO2t();
        $this->o2t->setUp();
        self::$logger->debug('END');
    }

    // Test functions

    public function testPrepareO2t(): void
    {
        $expected = $this->getFieldFromO2t("o2t");
        $actual   = $this->callMethodOnO2t('prepareO2t');

        self::assertNotEmpty($expected);
        self::assertNotEmpty($actual);
        self::assertInstanceOf(EasyGoingTestCaseDummyClazz::class, $expected);
        self::assertInstanceOf(EasyGoingTestCaseDummyClazz::class, $actual);
        self::assertNotSame($expected, $actual);
        self::assertEquals($expected, $actual);
    }

    public function testGetCasto2t(): void
    {
        $expected = $this->getFieldFromO2t("o2t");
        $actual   = $this->callMethodOnO2t('getCasto2t');

        self::assertNotEmpty($expected);
        self::assertNotEmpty($actual);
        self::assertInstanceOf(EasyGoingTestCaseDummyClazz::class, $expected);
        self::assertInstanceOf(EasyGoingTestCaseDummyClazz::class, $actual);
        self::assertEquals($expected, $actual);
        self::assertSame($expected, $actual);
    }

    public function testTestInit(): void
    {
        try {
            $this->o2t->testInit();
        } catch (\Exception $except) {
            self::fail(sprintf('FAIL: Should not raise any exection: %s', $except->getMessage()));
        }
    }

    /**
     * @param bool   $expectedBool
     * @param string $constName
     *
     * @dataProvider providerConstant
     */
    public function testIsConstExist(bool $expectedBool, string $constName): void
    {
        self::$logger->debug('parameters', [$expectedBool, $constName]);

        $actual = $this->o2t::publicIsConstExist($this->o2t->publicGetCastO2t(), $constName);

        self::$logger->info('Testcase comparing', [self::dataName(), $expectedBool, $actual]);

        self::assertEquals($expectedBool, $actual, sprintf("Not equals: '%s'='%s'", "$expectedBool", "$actual"));
    }

    /**
     * @param bool   $expectedBool
     * @param string $constName
     * @param string $expected
     *
     * @dataProvider providerConstant
     */
    public function testGetConstValue(bool $expectedBool, string $constName, string $expected): void
    {
        self::$logger->debug('parameters', [$expectedBool,$constName]);

        $actual = $this->o2t::publicGetConstValue($this->o2t->publicGetCastO2t(), $constName);

        self::$logger->info('Testcase comparing', [self::dataName(), $expected,$actual]);

        self::assertEquals($expected, $actual, sprintf("Not equals: '%s'='%s'", $expected, $actual));
    }

    public function testIsPrimitive(): void
    {
        $expected = true;
        $var = 100;

        $actual = $this->o2t::publicIsPrimitive($var);

        self::assertEquals($expected, $actual);
    }

    public function testGetAllDefinedConsts(): void
    {
        $expectedSize = 3;
        $clazz = get_class($this->o2t);

        $actual = $this->o2t::publicGetAllDefinedConsts($clazz);

        self::assertCount($expectedSize, $actual);
    }

    public function testGet_called_clazz(): void
    {
        $expected = get_class($this->o2t);

        $actual = $this->o2t::publicGet_called_clazz();

        self::assertEquals($expected, $actual);
    }

    public function testVerifyConstExists(): void
    {
        $constantName = get_class($this->o2t) . '::C_TEST';

        try {
            $this->o2t->publicVerifyConstExists($constantName);
        } catch (\Exception $exception) {
            self::fail(sprintf('FAIL: Should not raise any exection: %s', $exception->getMessage()));
        }
    }

    public function testGet_called_function(): void
    {
        $expected = 'publicGet_called_function';

        $actual = $this->o2t->publicGet_called_function();

        self::assertEquals($expected, $actual);
    }

    // Data Provider

    /**
     * @return array<mixed,mixed>
     */
    public function providerConstant()
    {
        return [
            'public' => [ true, EasyGoingTestCaseDummyClazz::TEST_CONST_PREFIX . '_PUBLIC', 'public'],
            'protected' => [ true, EasyGoingTestCaseDummyClazz::TEST_CONST_PREFIX . '_PROTECTED', 'protected'],
            'private' => [ true, EasyGoingTestCaseDummyClazz::TEST_CONST_PREFIX . '_PRIVATE', 'private'],
            'notexist' => [ false, EasyGoingTestCaseDummyClazz::TEST_CONST_PREFIX . '_NOTEXISTS', '']
        ];
    }
}
