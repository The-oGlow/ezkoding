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

namespace ollily\Tools\Test;

use ollily\Tools\String\ToStringTrait;

/**
 * A simple object usable as test object.
 */
class TestDataFoo
{
    use ToStringTrait;

    private mixed $fooValue;

    /**
     * @param null|int|string $fooValue
     *
     * @psalm-param 'KEY-ALPHA1'|11|22|33|null $fooValue
     */
    public static function init(string|int|null $fooValue = null): TestDataFoo
    {
        return new TestDataFoo($fooValue);
    }

    public function __construct(mixed $fooValue = null)
    {
        $this->fooValue = $fooValue;
    }

    #[\Override]
    protected function __toStringValues(): mixed // NOSONAR: php:S100
    {
        return $this->fooValue;
    }
}
