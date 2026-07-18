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

use Ds\Set;

trait MagicPublicFunctionTrait
{
    /**
     * @return Set<string>
     *
     * @phpstan-return Set<non-empty-string>
     */
    final public static function existingMethodNames(): Set
    {
        $callback = function (\ReflectionMethod $method): string {
            return $method->getName();
        };
        $availableMethodNames = array_map($callback, self::existingMethods()->toArray());

        return new Set($availableMethodNames);
    }

    /**
     * @return Set<\ReflectionMethod> All public methods of this clazz
     */
    final public static function existingMethods(): Set
    {
        $callback = function (\ReflectionMethod $method): bool {
            $notAllowed = new Set(['__call', 'existingMethodNames', 'existingMethods']);

            return !$notAllowed->contains($method->getName());
        };

        $availableMethods = self::collectPublicMethods(static::class);
        $availableMethods = array_filter($availableMethods, $callback);

        return new Set($availableMethods);
    }

    /**
     * @param string             $methodName Name of the called function
     * @param array<mixed,mixed> $arguments  All arguments send with the function
     *
     * @phpstan-param non-empty-string $methodName
     *
     * @return mixed
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
     * @param string $clazzName Name of the clazz
     *
     * @phpstan-param class-string $clazzName
     *
     * @return array<mixed,\ReflectionMethod> All public methods of {@link $clazzName)
     */
    final protected static function collectPublicMethods(string $clazzName): array
    {
        $publicMethods = [];

        $reflectObj = new \ReflectionClass($clazzName);
        /** @var array<mixed,\ReflectionMethod> */
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
     * @param object             $instance   The current instance
     * @param string             $methodName Name of the called function
     * @param array<mixed,mixed> $arguments  All arguments send with the function
     *
     * @return mixed Result of the called method
     */
    final protected static function callThatMethod(object $instance, string $methodName, array $arguments): mixed
    {
        $reflectMethod = new \ReflectionMethod($instance, $methodName);
        echo sprintf("\nCalling '%s'->'%s'\n", get_class($instance), $methodName);

        return $reflectMethod->invokeArgs($instance, $arguments);
    }
}
