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
use ReflectionException;

class ClazzHelper
{
    private function __construct()
    {
        // Hide the public constructor
    }

    /**
     * @param string $clazz
     *
     * @phpstan-param class-string $clazz
     *
     * @return string
     */
    public static function getClazzFile(string $clazz): string
    {
        $file = '';

        try {
            $reflection = new ReflectionClass($clazz);
            $file = $reflection->getFileName();
            if (false === $file) {
                $file = '';
            }
        } catch (ReflectionException $e) {
            // nothing to do
        }

        return $file;
    }

    /**
     * @param string $clazz
     *
     * @phpstan-param class-string $clazz
     *
     * @return string
     */
    public static function getClazzPath(string $clazz): string
    {
        $file = self::getClazzFile($clazz);

        return pathinfo($file, PATHINFO_DIRNAME);
    }

    /**
     * @param string $clazz
     *
     * @phpstan-param class-string $clazz
     *
     * @return string
     */
    public static function getClazzFilename(string $clazz): string
    {
        $file = self::getClazzFile($clazz);

        return pathinfo($file, PATHINFO_FILENAME);
    }

    /**
     * Retrieve all child classes of a given class.
     *
     * @param mixed $clazzName
     *
     * @return string[]
     */
    public static function getAllChildren(mixed $clazzName): array
    {
        $children = [];
        foreach (get_declared_classes() as $currentClazz) {
            if (is_subclass_of($currentClazz, $clazzName)) {
                $children[] = $currentClazz;
            }
        }

        return $children;
    }
}
