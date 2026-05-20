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

/**
 * A simple clazz which will be tested by the test clazz.
 *
 * @see UnavailableMethodsTraitTestO2tClazz
 * @see UnavailableMethodsTraitTestWrongO2tClazz
 *
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
class UnavailableMethodsTraitDummyClazz
{
    public function publicFunc(): string
    {
        return 'publicFuncValue';
    }

    protected function protectedFunc(): string
    {
        return 'protectedFuncValue';
    }

    private function privateFunc(): string
    {
        return 'privateFuncValue';
    }
}
