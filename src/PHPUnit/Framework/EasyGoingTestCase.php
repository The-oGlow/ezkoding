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

abstract class EasyGoingTestCase extends TestCase
{
    /** var string Separator for static access */
    public const string    C_STATIC_SEP = '::';

    /** @var string All primitive datatypes */
    protected const string C_PRIMITIVES = 'int|integer|bool|boolean|float';

    private static LoggerInterface $logger;

    /** @var mixed The object which will be tested. */
    protected mixed $o2t;

    /**
     * @return mixed new created instance
     */
    abstract protected static function prepareO2t(): mixed;

    /**
     * @return mixed current instance
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

    protected static function isPrimitive(mixed $var): bool
    {
        $primitive = false;

        if (isset($var) && strpos(self::C_PRIMITIVES, gettype($var)) > 0) {
            $primitive = true;
        }

        return $primitive;
    }

    /**
     * @param mixed $clazzName
     *
     * @return array<mixed,mixed>
     */
    protected static function getAllDefinedConsts(mixed $clazzName): array
    {
        $instance = new \ReflectionClass($clazzName);

        return $instance->getConstants(); // NOSONAR: php:S3011
    }

    protected static function isConstExist(mixed $clazz, string $constantName): bool
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

    protected static function getConstValue(mixed $clazz, string $constantName): mixed
    {
        self::$logger->debug('START');

        try {
            $constantValue = constant($constantName);
            self::$logger->debug('Recieved by constant()', [$constantName]);
        } catch (\Throwable $e) {
            self::$logger->debug('Cannot get value by constant()', [$constantName]);
        }
        if (!isset($constantValue)) {
            $reflectionClazz = new \ReflectionClass($clazz);
            $splitClazz      = explode(self::C_STATIC_SEP, $constantName);
            $constantValue   = $reflectionClazz->getConstant($splitClazz[count($splitClazz) - 1]); // NOSONAR: php:S3011
            self::$logger->debug('Recieved by reflection', [$constantName]);
        }

        self::$logger->debug('END');

        return $constantValue;
    }

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
