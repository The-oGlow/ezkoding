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

namespace ollily\Tools\Reflection\UnavailableMethodsTraitTest;

use ollily\Tools\Reflection\UnavailableMethodsTrait;

/**
 * This is the test clazz which will be tested.
 *
 * @see  UnavailableMethodsTraitDummyClazz
 * @see  UnavailableMethodsTraitTest
 */
class UnavailableMethodsTraitTestO2tClazz
{
    use UnavailableMethodsTrait;

    protected UnavailableMethodsTraitDummyClazz $o2t;

    public function __construct()
    {
        $this->o2t = new UnavailableMethodsTraitDummyClazz();
    }

    public function publicCallMethodOnO2t(string $methodName): mixed
    {
        return $this->callMethodOnO2t($methodName);
    }

    public function publicCallMethodByReflection(string $methodName): mixed
    {
        return $this->callMethodByReflection(UnavailableMethodsTraitDummyClazz::class, $methodName, $this->o2t);
    }
}
