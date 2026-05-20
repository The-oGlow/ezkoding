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

use Monolog\ConsoleLogger;
use Psr\Log\LoggerInterface;

abstract class EasyGoingTestCase extends TestCase
{
    /** var string Separator for static access */
    public const    C_STATIC_SEP = '::';

    /** @var string All primitive datatypes */
    protected const C_PRIMITIVES = 'int|integer|bool|boolean|float';

    /** @var LoggerInterface */
    private static $logger;

    /** @var mixed The object which will be tested. */
    protected $o2t;

    /**
     * @return mixed
     */
    abstract protected static function prepareO2t();

    /**
     * @return mixed
     */
    abstract protected function getCasto2t();

    /**
     * @param mixed   $name
     * @param mixed[] $data
     * @param string  $dataName
     */
    public function __construct($name = null, $data = [], $dataName = '')
    {
        self::$logger = new ConsoleLogger(EasyGoingTestCase::class);
        self::$logger->debug('START');

        parent::__construct($name, $data, $dataName);

        $testInfo = [ $this->get_called_function(),self::get_called_clazz()];
        self::$logger->debug('calledFunction,calledClazz', $testInfo);

        self::$logger->debug('END');
    }

    public function setUp(): void
    {
        self::$logger->debug('START');

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
     * @param mixed $var
     *
     * @return bool
     */
    protected static function isPrimitive($var): bool
    {
        $primitive = false;

        if (isset($var) && strpos(self::C_PRIMITIVES, gettype($var)) > 0) {
            $primitive = true;
        }

        return $primitive;
    }

    /**
     * @param mixed $clazz
     *
     * @return mixed[]
     */
    protected static function getAllDefinedConsts($clazz): array
    {
        $clazz = new \ReflectionClass($clazz);

        return $clazz->getConstants(); // NOSONAR php:S3011
    }

    /**
     * @param mixed  $clazz
     * @param string $constantName
     *
     * @return bool
     */
    protected static function isConstExist($clazz, string $constantName): bool
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
            $allConsts  = self::getAllDefinedConsts($clazz);
            $splitClazz = explode(self::C_STATIC_SEP, $constantName);
            $isDefined  = isset($allConsts[$splitClazz[count($splitClazz) - 1]]);
            self::$logger->debug('Verify existence by reflection', [$constantName]);
        }

        self::$logger->debug('END');

        return $isDefined;
    }

    // Test functions

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
     * @param mixed  $clazz
     * @param string $constantName
     *
     * @return mixed
     */
    protected static function getConstValue($clazz, string $constantName)
    {
        self::$logger->debug('START');

        try {
            $constantValue = constant($constantName);
            self::$logger->debug('Recieved by constant()', [$constantName]);
        } catch (\Throwable $e) {
            self::$logger->debug('Cannot get value by constant()', [$constantName]);
        }
        if (!isset($constantValue)) {
            $reflectionClazz         = new \ReflectionClass($clazz);
            $splitClazz    = explode(self::C_STATIC_SEP, $constantName);
            $constantValue = $reflectionClazz->getConstant($splitClazz[count($splitClazz) - 1]); // NOSONAR php:S3011
            self::$logger->debug('Recieved by reflection', [$constantName]);
        }

        self::$logger->debug('END');

        return $constantValue;
    }

    /**
     * @param string $constantName
     *
     * @SuppressWarnings("PHPMD.ElseExpression")
     */
    protected function verifyConstExists(string $constantName): void
    {
        self::$logger->debug('START');

        $isDefined = self::isConstExist($this->o2t, $constantName);
        if ($isDefined) {
            $constantValue = self::getConstValue($this->o2t, $constantName);
            self::$logger->debug("Checking '$constantName'");
            if (static::isPrimitive($constantValue)) {
                self::assertGreaterThan(0, strlen("$constantValue"), sprintf("The primitive '%s'='%s'", $constantName, $constantValue));
            } else {
                self::assertNotEmpty($constantValue, sprintf('Constant \'%s\' is empty!', $constantName));
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
            /** @psalm-suppress RedundantCondition
             * @phpstan-ignore function.alreadyNarrowedType
             */
            if (is_array($debug) && !empty($debug)) {
                $calledFunction = $debug[1]['function'];
            }
        } catch (\Exception $exception) {
            // ignore
        }

        return $calledFunction;
    }
}
