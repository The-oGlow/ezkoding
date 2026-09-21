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
 * Helping functions for handling a clazz.
 *
 * @author ollily
 */
class ClazzHelper
{
    private function __construct()
    {
        // Hide the public constructor
    }

    /**
     * Returns the full filename of the clazz.
     *
     * @param string $clazzName The name of the clazz
     *
     * @return string The full filename or empty
     */
    public static function getClazzFile(string $clazzName): string
    {
        $file = '';

        try {
            /** @psalm-suppress ArgumentTypeCoercion
             * @phpstan-ignore argument.type */
            $reflection = new \ReflectionClass($clazzName);
            $file = $reflection->getFileName();
            if (false === $file) {
                $file = '';
            }
        } catch (\ReflectionException $e) {
            // nothing to do
        }

        return $file;
    }

    /**
     * Returns the path to the file of the clazz.
     *
     * @param string $clazzName The name of the clazz
     *
     * @return string The path to the file
     */
    public static function getClazzPath(string $clazzName): string
    {
        $file = self::getClazzFile($clazzName);

        return pathinfo($file, PATHINFO_DIRNAME);
    }

    /**
     * Returns the name of the file of the clazz.
     *
     * @param string $clazzName The name of the clazz
     *
     * @return string The name of the file
     */
    public static function getClazzFilename(string $clazzName): string
    {
        $file = self::getClazzFile($clazzName);

        return pathinfo($file, PATHINFO_FILENAME);
    }

    /**
     * Retrieve all child clazzes of a given clazz.
     *
     * @param string $clazzName The name of the clazz
     *
     * @return array<string> All child clazzes as array or empty array
     */
    public static function getAllChildren(mixed $clazzName): array
    {
        $children = [];
        /** @psalm-suppress ArgumentTypeCoercion */
        foreach (get_declared_classes() as $currentClazz) {
            if (is_subclass_of($currentClazz, $clazzName)) {
                $children[] = $currentClazz;
            }
        }

        return $children;
    }
}
