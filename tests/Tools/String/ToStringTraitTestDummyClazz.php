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

namespace ollily\Tools\String;

/*
 * A simple clazz which will be used by the test clazz.
 *
 * @see ToStringTraitTest
 */

class ToStringTraitTestDummyClazz
{
    use ToStringTrait;

    public mixed $greeting = 'hello';

    public function __construct(mixed $greeting = null)
    {
        if (!is_null($greeting)) {
            $this->greeting = $greeting;
        }
    }

    /**
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     */
    #[\Override]
    protected function __toStringValues(): mixed
    {
        return $this->greeting;
    }
}
