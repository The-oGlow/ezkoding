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

namespace ollily\Tools\String\ToStringTraitTest;

/*
 * A simple clazz which will be used by the test clazz.
 *
 * @see ToStringTraitTest
 */
use ollily\Tools\String\ToStringTrait;

class ToStringTraitTestDummyClazz
{
    use ToStringTrait;

    /** @var mixed */
    public $greeting = 'hello';

    /**
     * @param mixed $greeting
     */
    public function __construct($greeting = null)
    {
        if (!is_null($greeting)) {
            $this->greeting = $greeting;
        }
    }

    /**
     * @return mixed
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     */
    protected function __toStringValues()
    {
        return $this->greeting;
    }
}
