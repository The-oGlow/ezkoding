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

namespace ollily\Tools\Reflection;

use ReflectionMethod;

trait UnavailableMethodsTrait
{
    /**
     * Calls hidden method (private, protected, package) without parameters by reflection.
     *
     * @param mixed  $clazzName
     * @param string $methodName
     * @param mixed  $instance
     *
     * @return null|mixed
     */
    protected function callMethodByReflection($clazzName, string $methodName, $instance)
    {
        $result = null;
        if (!empty($clazzName)) {
            $refObject = new ReflectionMethod($clazzName, $methodName);
            $refObject->setAccessible(true); // NOSONAR: php:S3011

            $result = $refObject->invoke($instance);
        }

        return $result;
    }

    /**
     * Calls a hidden method on an object which shall be tested (o2t).
     *
     * @param string $methodName
     *
     * @return null|mixed
     */
    protected function callMethodOnO2t(string $methodName)
    {
        $result = null;

        /**
         * @psalm-suppress RedundantPropertyInitializationCheck
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
