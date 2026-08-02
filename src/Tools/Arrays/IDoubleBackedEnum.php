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

namespace ollily\Tools\Arrays;

interface IDoubleBackedEnum
{
    /**
     * @return int The integer representation
     */
    public function intValue(): int;

    /**
     * @return string The textual representation
     */
    public function text(): string;
    
    /**
     * @return mixed The object representation
     */
    public function objectValue() : mixed;
}
