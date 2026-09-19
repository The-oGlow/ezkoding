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

use BackedEnum;
use Monolog\EasyGoingLogger;
use Psr\Log\LoggerInterface;
use UnitEnum;

/**
 * The only testcase class you will ever need.
 *
 * @author ollily
 */
abstract class EasyGoingTestCase extends TestCase
{
    /** @var string Separator for static access */
    public const string    C_STATIC_SEP = '::';

    /** @var string All primitive datatypes */
    protected const string C_PRIMITIVES = 'int|integer|bool|boolean|float|double';

    /** @var LoggerInterface The logger for this clazz */
    private static LoggerInterface $logger;

    /** @var mixed The object which will be tested. */
    protected mixed $o2t;

    /**
     * Create and setup the testcase object.
     *
     * @return mixed A new created instance
     */
    abstract protected static function prepareO2t(): mixed;

    /**
     * Returns a typed reference to the testcase object.
     *
     * @return mixed Reference on the current instance
     */
    abstract protected function getCasto2t(): mixed;

    #[\Override]
    public static function setUpBeforeClass(): void
    {
        self::$logger = EasyGoingLogger::init(EasyGoingTestCase::class);
        self::$logger->debug('START');

        parent::setUpBeforeClass();

        self::$logger->debug('END');
    }

    #[\Override]
    public function setUp(): void
    {
        self::$logger->debug('START');

        $testInfo = [$this->get_called_function(), self::get_called_clazz()];
        self::$logger->debug('calledFunction,calledClazz', $testInfo);

        parent::setUp();
        $this->o2t = static::prepareO2t();

        self::$logger->debug('END');
    }

    // Static function

    /**
     * Tries to identify the name of the class, from where the testcase was called.
     *
     * @return string the name of the calling class or empty
     */
    protected static function get_called_clazz(): string
    {
        $calledClazz = '';

        try {
            $calledClazz = get_called_class();
        } catch (\Exception $exception) { // @phpstan-ignore catch.neverThrown
            // ignore
        }

        return $calledClazz;
    }

    /**
     * Checks, if the given value is a primitive datatype.
     *
     * @param mixed $var The value to check
     *
     * @return bool TRUE=the value is a primitive datatype, else FALSE
     *
     * @see EasyGoingTestCase::C_PRIMITIVES
     */
    protected static function isPrimitive(mixed $var): bool
    {
        $primitive = false;

        if (isset($var) && strpos(self::C_PRIMITIVES, gettype($var)) > 0) {
            $primitive = true;
        }

        return $primitive;
    }

    /**
     * Returns a list of all public constants in the clazz.
     *
     * @param mixed $clazzName The name of the clazz
     *
     * @return array<mixed> Array of all public constants in the clazz
     *
     * @see EasyGoingTestCase::filterConsts()
     */
    protected static function getAllDefinedConsts(mixed $clazzName): array
    {
        $instance = new \ReflectionClass($clazzName);

        return $instance->getConstants(); // NOSONAR: php:S3011
    }

    /**
     * Checks, if a constant exists in a clazz.
     *
     * @param mixed  $clazzName    The name of the clazz to check
     * @param string $constantName The name of the constant to check
     *
     * @return bool TRUE=the constant exists in the clazz, else FALSE
     *
     * @see EasyGoingTestCase->verifyConstExists()
     */
    protected static function isConstExist(mixed $clazzName, string $constantName): bool
    {
        self::$logger->debug('START');

        try {
            $isDefined = defined($constantName);
            self::$logger->debug('Check existence by defined()', [$constantName]);
        } catch (\Throwable $e) {
            self::$logger->info('Cannot check existence by defined()', [$constantName]);
            $isDefined = false;
        }
        if (!$isDefined) {
            $allConsts  = self::getAllDefinedConsts($clazzName);
            $splitClazz = explode(self::C_STATIC_SEP, $constantName);
            $isDefined = isset($allConsts[$splitClazz[count($splitClazz) - 1]]);
            self::$logger->debug('Verify existence by reflection', [$constantName]);
        }

        self::$logger->debug('END');

        return $isDefined;
    }

    /**
     * Returns a filtered list of all public constants in the clazz.
     *
     * @param string $filterTerm A filter text matching a constant from the beginning
     * @param mixed  $clazzName  The name of the clazz
     *
     * @see EasyGoingTestCase::getAllDefinedConsts()
     *
     * @phpstan-param class-string $clazzName
     *
     * @return array<mixed> Array of all public constants in the clazz
     */
    protected static function filterConsts(string $filterTerm, mixed $clazzName): array
    {
        $callback = function (mixed $val, mixed $key) use ($filterTerm): bool {
            return str_starts_with($key, $filterTerm);
        };
        $consts = array_filter(self::getAllDefinedConsts($clazzName), $callback, ARRAY_FILTER_USE_BOTH);
        $constsMap = function (mixed $val) use ($clazzName): string {
            return $clazzName . self::C_STATIC_SEP . $val;
        };

        return array_map($constsMap, array_keys($consts));
    }

    /**
     * Returns the value of a constants.
     *
     * @param mixed  $clazzName    The name of the clazz to check
     * @param string $constantName The name of the constant to check
     *
     * @return mixed The value of the constants
     */
    protected static function getConstValue(mixed $clazzName, string $constantName): mixed
    {
        self::$logger->debug('START');

        try {
            $constantValue = constant($constantName);
            self::$logger->debug('Recieved by constant()', [$constantName]);
        } catch (\Throwable $e) {
            self::$logger->debug('Cannot get value by constant()', [$constantName]);
        }
        if (!isset($constantValue)) {
            $reflectionClazz = new \ReflectionClass($clazzName);
            $splitClazz      = explode(self::C_STATIC_SEP, $constantName);
            $constantValue   = $reflectionClazz->getConstant($splitClazz[count($splitClazz) - 1]); // NOSONAR: php:S3011
            self::$logger->debug('Recieved by reflection', [$constantName]);
        }

        self::$logger->debug('END');

        return $constantValue;
    }

    // Test functions

    /**
     * Basic test, if test object ist instantiated.
     */
    public function testInit(): void
    {
        self::$logger->debug('START');

        self::assertNotEmpty($this->o2t);
        self::assertIsObject($this->o2t);
        self::assertInstanceOf(get_class($this->o2t), static::prepareO2t());

        self::$logger->debug('END');
    }

    // Misc functions

    /**
     * Checks, if a constant exists in the current instance.
     *
     * @param string $constantName The name of the constant to check
     *
     * @see EasyGoingTestCase::isConstExist()
     */
    protected function verifyConstExists(string $constantName): void
    {
        self::$logger->debug('START');

        $isDefined = self::isConstExist($this->o2t, $constantName);
        if ($isDefined) {
            self::$logger->debug("Checking '$constantName'");
            $constantValue = self::getConstValue($this->o2t, $constantName);
            if (static::isPrimitive($constantValue)) {
                self::assertGreaterThanOrEqual(0, strlen("$constantValue"), sprintf("The primitive '%s'='%s'", $constantName, $constantValue));
            } else {
                if ($constantValue instanceof UnitEnum) {
                    if ($constantValue instanceof BackedEnum) {
                        $constantValue = $constantValue->value;
                    } else {
                        $constantValue = $constantValue->name;
                    }
                }
                if (self::isPrimitive($constantValue)) {
                    self::assertGreaterThanOrEqual(0, strlen("$constantValue"), sprintf("Primitive '%s'='%s'", $constantName, $constantValue));
                } elseif (is_array($constantValue)) {
                    self::assertGreaterThanOrEqual(0, count($constantValue), sprintf("Array '%s'='%s'", $constantName, implode($constantValue)));
                } else {
                    self::assertGreaterThanOrEqual(0, strlen("$constantValue"));
                }
            }
        } else {
            self::fail(sprintf("FAIL: Constant '%s' not exists", $constantName));
        }

        self::$logger->debug('END');
    }

    /**
     * Tries to identify the name of the function, from where the testcase was called.
     *
     * @return string the name of the calling function or empty
     */
    protected function get_called_function(): string
    {
        $calledFunction = '';

        try {
            $debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            if (!empty($debug)) {
                $calledFunction = $debug[1]['function'];
            }
        } catch (\Exception $exception) {
            // ignore
        }

        return $calledFunction;
    }
}
