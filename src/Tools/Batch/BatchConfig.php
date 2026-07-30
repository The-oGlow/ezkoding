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
 * @phpstan-import-type ConfigKey from IBatchConfig
 * @phpstan-import-type ConfigData from IBatchConfig
 */
class BatchConfig implements IBatchConfig
{
    /** @var Map<mixed,mixed>
     * @phpstan-var Map<ConfigKey,ConfigData> */
    private Map $fullConfig;

    /**
     * @param Map<mixed,mixed> $fullConfig
     *
     * @phpstan-param Map<ConfigKey,ConfigData> $fullConfig
     */
    public function __construct(Map $fullConfig)
    {
        $this->fullConfig = $fullConfig;
    }

    #[\Override]
    public function getFullConfig(): Map
    {
        return $this->fullConfig;
    }

    #[\Override]
    public function getConfig(mixed $key): mixed
    {
        return $this->fullConfig->get($key, null);
    }

    #[\Override]
    public function setConfig(mixed $key, mixed $value): void
    {
        $this->fullConfig->put($key, $value);
    }
}
