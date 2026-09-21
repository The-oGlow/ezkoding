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

/**
 * Provides access on non public methods in a clazz.
 *
 * @author ollily
 */
trait UnavailableMethodsTrait
{
    /**
     * Calls hidden method (private, protected, package) without parameters by reflection.
     *
     * @param mixed  $clazzName  The name of the clazz
     * @param string $methodName The name of the method to call
     * @param mixed  $instance   The current instance to call on
     *
     * @return mixed The result of the called method
     */
    protected function callMethodByReflection(mixed $clazzName, string $methodName, mixed $instance): mixed
    {
        $result = null;
        if (!empty($clazzName)) {
            $refObject = new \ReflectionMethod($clazzName, $methodName);
            $refObject->setAccessible(true); // NOSONAR: php:S3011

            $result = $refObject->invoke($instance);
        }

        return $result;
    }

    /**
     * Calls a hidden method on an instance of the test object (o2t).
     *
     * @param string $methodName The name of the method to call
     *
     * @return mixed The result of the called method
     */
    protected function callMethodOnO2t(string $methodName): mixed
    {
        $result = null;

        /**
         * @psalm-suppress RedundantPropertyInitializationCheck,UndefinedThisPropertyFetch
         * @phpstan-ignore isset.property,property.notFound
         */
        if (isset($this->o2t)) {
            $clazzName = get_class($this->o2t);
            /** @psalm-suppress RedundantCondition */
            if (!empty($clazzName)) {
                $result = $this->callMethodByReflection($clazzName, $methodName, $this->o2t);
            }
        }

        return $result;
    }
}
