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

namespace ollily\Common;

use Ds\Map;

/**
 * @author ollily
 */
class AbstractSingletonTestDummyClazz extends AbstractSingleton
{
    // Change visibility

    /**
     * @param Map<mixed,mixed> $overrideParameters
     */
    public function publicPrepareSettings(Map $overrideParameters): void
    {
        $this->prepareSettings($overrideParameters);
    }

    /**
     * @param Map<mixed,mixed> $overrideParameters
     *
     * @return bool
     */
    public function publicValidateSettings(Map $overrideParameters): bool
    {
        return $this->validateSettings($overrideParameters);
    }

    /**
     * @return string
     */
    public function publicPrepareShortOpts(): string
    {
        return $this->prepareShortOpts();
    }

    /**
     * @return array<mixed>
     */
    public function publicPrepareLongOpts(): array
    {
        return $this->prepareLongOpts();
    }

    /**
     * @param Map<mixed, mixed> $overrideParameters
     * @param string            $keyName
     *
     * @return mixed
     */
    public function publicParseBoolMap(Map $overrideParameters, string $keyName): mixed
    {
        return $this->parseBool($overrideParameters, $keyName);
    }
}
