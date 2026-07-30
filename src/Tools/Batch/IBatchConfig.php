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

/**
 * @phpstan-type ConfigKey mixed
 * @phpstan-type ConfigData mixed
 */
interface IBatchConfig
{
    /**
     * @return Map<mixed,mixed>
     *
     * @phpstan-return Map<ConfigKey,ConfigData>
     */
    public function getFullConfig(): Map;

    /**
     * @param mixed $key
     *
     * @phpstan-param ConfigKey $key
     *
     * @return mixed
     *
     * @phpstan-return ConfigData
     */
    public function getConfig(mixed $key): mixed;

    /**
     * @param mixed $key
     * @param mixed $value
     *
     * @oaram mixed $value
     *
     * @phpstan-param ConfigKey  $key
     * @phpstan-param ConfigData $value
     */
    public function setConfig(mixed $key, mixed $value): void;
}
