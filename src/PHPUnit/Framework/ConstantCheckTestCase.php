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

namespace PHPUnit\Framework;

use Monolog\EasyGoingLogger;
use Psr\Log\LoggerInterface;

abstract class ConstantCheckTestCase extends EasyGoingTestCase
{
    public const int DIFF_ZERO        = 0;

    public const int INIT_CONST_COUNT = 0;

    public const bool INIT_CROSSCHECK  = false;

    /**
     * @var bool TRUE=Execute a constants cross check (Default: FALSE)
     *
     * @see ConstantCheckTestCase::expectedConstsCount
     * @see ConstantCheckTestCase::CHECK_INIT
     */
    private static bool $withConstCrossCheck = self::INIT_CROSSCHECK;

    /**
     * @var int Set the expected and correct count of constants in child classes. Only used if {@link ConstantCheckTestCase::$withConstCrossCheck}=true.
     *
     * @see ConstantCheckTestCase::$withConstCrossCheck
     * @see ConstantCheckTestCase::testAllConstants()
     */
    private static int $expectedConstsCount = self::INIT_CONST_COUNT;

    /** @var array Array of the names of all constants in the class. */
    private static array $actualConsts = [];

    private static LoggerInterface $logger;

    /**
     * Inits the constants crosscheck.
     *
     * @param bool $withConstCrossCheck
     * @param int  $expectedConstsCount
     *
     * @see ConstantCheckTestCase::$withConstCrossCheck
     * @see ConstantCheckTestCase::$expectedConstsCount
     */
    #[\Override]
    public static function setUpBeforeClass(bool $withConstCrossCheck = self::INIT_CROSSCHECK, int $expectedConstsCount = self::INIT_CONST_COUNT): void
    {
        self::$logger = EasyGoingLogger::init(ConstantCheckTestCase::class);
        self::$logger->debug('START');

        parent::setUpBeforeClass();
        $testInfo                  = [self::$withConstCrossCheck, self::$expectedConstsCount, self::get_called_clazz()];
        self::$actualConsts        = [];
        self::$withConstCrossCheck = $withConstCrossCheck;
        self::$expectedConstsCount = $expectedConstsCount;
        self::$logger->notice('withConstCrossCheck,expectedConstCount,calledClazz', $testInfo);

        self::$logger->debug('END');
    }

    /**
     * Performs the constants crosscheck at the end.
     */
    #[\Override]
    public static function tearDownAfterClass(): void
    {
        self::$logger->debug('START');

        parent::tearDownAfterClass();
        self::crossCheckConstants(get_class(static::prepareO2t()), self::$actualConsts);
        self::$actualConsts        = [];
        self::$withConstCrossCheck = self::INIT_CROSSCHECK;
        self::$expectedConstsCount = self::INIT_CONST_COUNT;

        self::$logger->debug('END');
    }

    // Static functions

    /**
     * @return bool TRUE=constant crosscheck is activated, else FALSE
     */
    public static function isWithConstCrossCheck(): bool
    {
        return self::$withConstCrossCheck;
    }

    /**
     * Executes the constant crosscheck  and fails if a constant is not found or not expected to exist.
     *
     * @param mixed $clazz        the clazz having the constants to check
     * @param array $actualConsts an array of the already found constants
     *
     * @see ConstantCheckTestCase::$withConstCrossCheck
     */
    protected static function crossCheckConstants(mixed $clazz, array $actualConsts): void
    {
        self::$logger->debug('START');

        if (self::$withConstCrossCheck) {
            self::$logger->notice('CrossCheck is active', [$clazz]);
            $expected = self::getAllDefinedConsts($clazz);
            ksort($expected);
            $expected = array_keys($expected);

            $callback = /**
             * @param mixed $value
             *
             * @return string
             */
                function (mixed $value): string {
                    $res = '';
                    if (is_string($value) && str_contains($value, self::C_STATIC_SEP)) {
                        try {
                            $startPos = ((int)strpos($value, self::C_STATIC_SEP)) + strlen(self::C_STATIC_SEP);
                            $res      = substr($value, $startPos);
                        } catch (\Throwable $exception) {
                            self::$logger->error(sprintf("%s: '%s'", $exception->getMessage(), $value));
                        }
                    } else {
                        self::$logger->error(sprintf("Value has no '%s': '%s'", self::C_STATIC_SEP, $value));
                    }

                    return $res;
                };

            self::$logger->info(' * Remove clazz prefix');
            /** @var string[] */
            $actual = array_map($callback, $actualConsts);
            self::$logger->info(' * Flip constants');
            $actual = array_flip($actual);
            self::$logger->info(' * Sort constants');
            ksort($actual);
            self::$logger->info(' * Get array keys');
            $actual = array_keys($actual);

            self::$logger->info(' * Verify expected vs actual constants');
            $difference = array_merge(array_diff($expected, $actual), array_diff($actual, $expected));
            self::$logger->debug(' * expected  :', [$expected]);
            self::$logger->debug(' * actual    :', [$actual]);
            self::$logger->debug(' * difference:', [count($difference), $difference]);
            self::$logger->info('CrossCheck ended', [count($difference) == self::DIFF_ZERO, $clazz]);
            self::assertCount(
                self::DIFF_ZERO,
                $difference,
                'You have forgotten to check: ' . var_export(array_diff($expected, $actual), true)
            );
        }

        self::$logger->debug('END');
    }

    /**
     * Adds an array of constants which have been found.
     *
     * @param null|array $checkedConsts array of found constants
     *
     * @see ConstantCheckTestCase::$withConstCrossCheck
     */
    protected static function updateActualConsts(?array $checkedConsts): void
    {
        if (self::$withConstCrossCheck && !is_null($checkedConsts)) {
            self::$actualConsts = array_merge(self::$actualConsts, $checkedConsts);
        }
    }

    /**
     * Checks, if {@link $allDefinedConsts) has the size of {@link $expectedCount}.
     *
     * @param int   $expectedCount    count of constants which must exists
     * @param array $allDefinedConsts an array with all defined constants
     *
     * @return array<mixed> [true|false, count($allDefinedConsts)]
     *
     * @see ConstantCheckTestCase::$withConstCrossCheck
     */
    protected static function checkConstantsCount(int $expectedCount, array $allDefinedConsts): array
    {
        self::$logger->debug('START');

        $allCount = count($allDefinedConsts);
        if (self::$withConstCrossCheck) {
            $result = $expectedCount == $allCount;
        } else {
            $result = true;
        }

        self::$logger->debug('END');

        return [$result, $allCount];
    }

    // Test functions

    /**
     * Checks, if the count of found constants matches to the exepected count of constants.
     *
     * @see ConstantCheckTestCase::$expectedConstsCount
     */
    public function testAllConstants(): void
    {
        self::$logger->debug('START');

        $allDefinedConsts = self::getAllDefinedConsts(get_class(static::prepareO2t()));
        ksort($allDefinedConsts);
        [$actual, $actualConstsCount] = self::checkConstantsCount(self::$expectedConstsCount, $allDefinedConsts);

        self::assertTrue(
            $actual,
            sprintf('Constants, expected count is not reached by actual count [%s, %s] ', self::$expectedConstsCount, $actualConstsCount)
        );

        self::$logger->debug('END');
    }

    // Misc functions

    /**
     * Checks, if all constants exists.
     * <code>['CONST1','CONST2',...]</code>.
     *
     * @param array $constants an array with constants to check
     */
    protected function verifyConstAllExists(array $constants = []): void
    {
        self::$logger->debug('START');

        foreach ($constants as $constant) {
            $this->verifyConstExists($constant);
        }

        self::$logger->debug('END');
    }

    /**
     * Checks, if all constants exists are arrays and have the expected size.
     * <code>['CONST1'=>3,'CONST2'=>10,...]</code>.
     *
     * @param array $constants an array with constants and expected sizes to check
     */
    protected function verifyConstArrayAllExists(array $constants = []): void
    {
        self::$logger->debug('START');

        foreach ($constants as $constant => $expectedSize) {
            $this->verifyConstExists($constant);
            $this->verifyConstArraySize($constant, $expectedSize);
        }

        self::$logger->debug('END');
    }

    /**
     * Checks, if a constant is an array and has the expected size.
     *
     * @param string $constantName Name of the constant
     * @param int    $expectedSize expected size of the constant
     */
    protected function verifyConstArraySize(string $constantName, int $expectedSize): void
    {
        self::$logger->debug('START');

        $constantValue = self::getConstValue($this->o2t, $constantName);
        self::assertIsArray($constantValue, sprintf('Constant \'%s\' is not an array!', $constantName));
        self::assertCount($expectedSize, $constantValue, sprintf('Constant \'%s\' array size is not matching!', $constantName));

        self::$logger->debug('END');
    }
}
