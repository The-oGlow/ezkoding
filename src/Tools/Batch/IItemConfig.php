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

namespace ollily\Tools\Batch;

use Ds\Map;

interface IItemConfig
{
    /**
     * @return Map<mixed,mixed>
     */
    public function getFullConfig(): Map;

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function getConfig(mixed $key): mixed;

    /**
     * @param mixed $key
     * @param mixed $value
     */
    public function setConfig(mixed $key, mixed $value): void;
}
