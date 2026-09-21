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
 * Provides access on non public fields in a clazz.
 *
 * @author ollily
 */
trait UnavailableFieldsTrait
{
    /**
     * Retrieves the value from hidden fields (private, protected, package) by reflection.
     *
     * @param mixed  $clazzName The name of the clazz
     * @param string $fieldName The name of the field to retrieve
     * @param mixed  $instance  The current instance to call on
     *
     * @return mixed The value of the field
     */
    protected function getFieldByReflection(mixed $clazzName, string $fieldName, mixed $instance): mixed
    {
        $result = null;
        if (!empty($clazzName)) {
            $refObject = new \ReflectionProperty($clazzName, $fieldName);
            $refObject->setAccessible(true); // NOSONAR: php:S3011

            $result = $refObject->getValue($instance);  // NOSONAR: php:S3011
        }

        return $result;
    }

    /**
     * Sets a new value on a hidden field by reflection.
     *
     * @param mixed  $clazzName The name of the clazz
     * @param string $fieldName The name of the field to set on
     * @param mixed  $instance  The current instance to set on
     * @param mixed  $newValue  The new value to set
     */
    protected function setFieldByReflection(mixed $clazzName, string $fieldName, mixed $instance, mixed $newValue): void
    {
        if (!empty($clazzName)) {
            $refObject = new \ReflectionProperty($clazzName, $fieldName);
            $refObject->setAccessible(true); // NOSONAR: php:S3011

            $refObject->setValue($instance, $newValue);  // NOSONAR: php:S3011
        }
    }

    /**
     * Retrieves the value of a hidden field on an instance of the test object (o2t).
     *
     * @param string $fieldName The name of the field to retrieve
     *
     * @return mixed The value of the field
     */
    protected function getFieldFromO2t(string $fieldName): mixed
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
                $result = $this->getFieldByReflection($clazzName, $fieldName, $this->o2t);
            }
        }

        return $result;
    }
}
