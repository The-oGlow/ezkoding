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

use ollily\Tools\Test\TestData;

/**
 * This is the test clazz which will be tested.
 *
 * @see  ImplodeTraitTest
 */
class ImplodeTraitTestDummyClazz
{
    use ImplodeTrait;

    /** @var array<mixed,mixed> */
    public array $traitData = TestData::ARRAY_ALPHA2;

    /** @var array<mixed,mixed> */
    public array $traitObject = TestData::ARRAY_EMPTY;

    public function __construct()
    {
        $this->traitObject[] = new ImplodeTraitTestFooClazz();
        $this->traitObject[] = [
            TestData::KEY_NUM1 => new ImplodeTraitTestFooClazz(),
            TestData::KEY_NUM2 => new ImplodeTraitTestFooClazz(),
        ];
    }
}
