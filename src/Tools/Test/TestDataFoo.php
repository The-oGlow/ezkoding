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

class TestDataFoo
{
    use ToStringTrait;

    /** @var null|mixed */
    private $fooValue;

    /**
     * @param null|mixed $fooValue
     *
     * @return TestDataFoo
     */
    public static function init($fooValue = null): TestDataFoo
    {
        return new TestDataFoo($fooValue);
    }

    /**
     * @param null|mixed $fooValue
     */
    public function __construct($fooValue = null)
    {
        $this->fooValue = $fooValue;
    }

    /**
     * @return mixed
     */
    protected function __toStringValues()
    {
        return $this;
    }
}
