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

use ReflectionClass;
use ReflectionMethod;

trait MagicPublicFunctionTrait
{
    /**
     * @param string             $methodName Name of the called function
     * @param array<mixed,mixed> $arguments  all arguments send with the function
     *
     * @return mixed
     */
    public function __call(string $methodName, array $arguments): mixed
    {
        $result = null;

        $availableMethods = $this->collectPublicMethods(get_class($this));

        if (in_array($methodName, $availableMethods, true)) {
            echo sprintf("\nMethod '%s' is found\n", $methodName);
            $result = $this->callThatMethod($this, $methodName, $arguments);
        } else {
            echo sprintf("\nMethod '%s' is not available\n", $methodName);
        }

        return $result;
    }

    /**
     * @param string $clazzName
     *
     * @phpstan-param class-string $clazzName
     *
     * @return array<mixed,mixed>
     */
    final protected function collectPublicMethods(string $clazzName): array
    {
        $publicMethods = [];

        $reflectObj = new ReflectionClass($clazzName);
        /** @var array<mixed,ReflectionMethod> */
        $foundMethods = $reflectObj->getMethods(ReflectionMethod::IS_PUBLIC);

        if (count($foundMethods) > 0) {
            /** @var ReflectionMethod $foundMethod */
            foreach ($foundMethods as $foundMethod) {
                if (!(($foundMethod->getModifiers() & ReflectionMethod::IS_ABSTRACT) == ReflectionMethod::IS_ABSTRACT)) {
                    $publicMethods[] = $foundMethod;
                }
            }
        }

        return $publicMethods;
    }

    /**
     * @param object             $instance   the current instance
     * @param string             $methodName Name of the called function
     * @param array<mixed,mixed> $arguments  all arguments send with the function
     *
     * @return mixed
     */
    final protected function callThatMethod(object $instance, string $methodName, array $arguments): mixed
    {
        $reflectMethod = new ReflectionMethod($instance, $methodName);
        echo sprintf("\nCalling '%s'->'%s'\n", get_class($instance), $methodName);

        return $reflectMethod->invokeArgs($instance, $arguments);
    }
}
