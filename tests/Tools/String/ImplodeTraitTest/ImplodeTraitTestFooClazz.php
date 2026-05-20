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

namespace ollily\Tools\String\ImplodeTraitTest;

use ollily\Tools\Test\TestData;

/**
 * A simple clazz which will be used by the dummy clazz.
 *
 * @see ImplodeTraitTestDummyClazz
 */
class ImplodeTraitTestFooClazz
{
    /** @var array<mixed,mixed> */
    public $dummyData =  TestData::ARRAY_NUM_KEY2;

    /** @var array<mixed,mixed> */
    public $dummyEmpty = TestData::ARRAY_EMPTY;
}
