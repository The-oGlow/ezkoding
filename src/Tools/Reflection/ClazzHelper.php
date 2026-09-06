<?php

/*
 * Copyright 2026 postm.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
     * @return string
     */
    public static function getClazzFile(string $clazz): string
    {
        $file = '';
        try {
            $reflection = new ReflectionClass($clazz);
            $file = $reflection->getFileName();
        } catch (ReflectionException $e) {
            // nothing to do
        }
        return $file;
    }

    public static function getClazzPath(string $clazz): string
    {
        $file = self::getClazzFile($clazz);
        return pathinfo($file, PATHINFO_DIRNAME);
    }

    public static function getClazzFilename(string $clazz): string
    {
        $file = self::getClazzFile($clazz);
        return pathinfo($file, PATHINFO_FILENAME);
    }
}
