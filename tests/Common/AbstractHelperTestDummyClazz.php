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

use Ds\Collection;

/**
 * @author ollily
 */
class AbstractHelperTestDummyClazz extends AbstractHelper
{
    /**
     * @inheritDoc
     */
    #[\Override]
    protected function validateSettings(Collection $overrideParameters): bool
    {
        $result = false;
        if ($overrideParameters->isEmpty()) {
            $result = true;
        }

        return $result;
    }

    // Change visibility

    /**
     * @param Collection<mixed,mixed> $overrideParameters
     *
     * @return bool
     */
    public function publicValidateSettings(Collection $overrideParameters): bool
    {
        return $this->validateSettings($overrideParameters);
    }
}
