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

namespace ollily\Tools\Reflection;

use Ds\Sequence;
use Ds\Vector;

/**
 * Extends the clazz to provide a list of all public methods of a clazz.
 *
 * @author ollily
 */
trait MagicPublicFunctionTrait
{
    /**
     * Returns all public method names as sequence.
     *
     * @return Vector<string> All public method names of this clazz
     */
    final public static function existingMethodNames(): Sequence
    {
        $callback = function (\ReflectionMethod $method): string {
            return $method->getName();
        };
        $availableMethodNames = array_map($callback, self::existingMethods()->toArray());

        return new Vector($availableMethodNames); // @phpstan-ignore return.type
    }

    /**
     * Returns all public methods as sequence.
     *
     * @return Vector<\ReflectionMethod> All public methods of this clazz
     */
    final public static function existingMethods(): Sequence
    {
        $notAllowed = new Vector(['__call', 'existingMethodNames', 'existingMethods']);
        $callback = function (\ReflectionMethod $method) use ($notAllowed): bool {
            return !$notAllowed->contains($method->getName());
        };

        $availableMethods = self::collectPublicMethods(static::class);
        $availableMethods = array_filter($availableMethods, $callback);

        return new Vector($availableMethods);
    }

    /**
     * Entrypoint for magic calls.
     *
     * @param string       $methodName Name of the called method
     * @param array<mixed> $arguments  All arguments send with the method
     *
     * @return mixed Result of the magic called method
     */
    public function __call(string $methodName, array $arguments): mixed
    {
        $result = null;

        $availableMethods = self::existingMethodNames();

        if ($availableMethods->contains($methodName)) {
            echo sprintf("\nMethod '%s' is found\n", $methodName);
            $result = self::callThatMethod($this, $methodName, $arguments);
        } else {
            echo sprintf("\nMethod '%s' is not available\n", $methodName);
        }

        return $result;
    }

    /**
     * Collecting the public methods of a clazz.
     *
     * @param string $clazzName Name of the clazz
     *
     * @phpstan-param class-string $clazzName
     *
     * @return array<\ReflectionMethod> All public methods of {@link $clazzName)
     */
    final protected static function collectPublicMethods(string $clazzName): array
    {
        $publicMethods = [];

        $reflectObj = new \ReflectionClass($clazzName);
        /** @var array<\ReflectionMethod> */
        $foundMethods = $reflectObj->getMethods(\ReflectionMethod::IS_PUBLIC);

        if (count($foundMethods) > 0) {
            /** @var \ReflectionMethod $foundMethod */
            foreach ($foundMethods as $foundMethod) {
                if (($foundMethod->getModifiers() & \ReflectionMethod::IS_ABSTRACT) !== \ReflectionMethod::IS_ABSTRACT) {
                    $publicMethods[] = $foundMethod;
                }
            }
        }

        return $publicMethods;
    }

    /**
     * Call a magic method in this instance of the clazz.
     *
     * @param object       $instance   The current instance
     * @param string       $methodName Name of the called method
     * @param array<mixed> $arguments  All arguments send with the method
     *
     * @return mixed Result of the magic called method
     */
    final protected static function callThatMethod(object $instance, string $methodName, array $arguments): mixed
    {
        $reflectMethod = new \ReflectionMethod($instance, $methodName);
        echo sprintf("\nCalling '%s'->'%s'\n", get_class($instance), $methodName);

        return $reflectMethod->invokeArgs($instance, $arguments);
    }
}
