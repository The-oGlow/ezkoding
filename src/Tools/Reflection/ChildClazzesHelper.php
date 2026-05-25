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

class ChildClazzesHelper
{
    private function __construct()
    {
        // Hide the public constructor
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
