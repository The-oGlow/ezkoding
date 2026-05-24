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
class UnavailableMethodsTraitTestWrongO2tClazz
{
    use UnavailableMethodsTrait;

    /**
     * @var mixed
     *
     * @SuppressWarnings("PHPMD.UnusedPrivateField")
     */
    private mixed $wrongO2t;

    public function __construct()
    {
        $this->wrongO2t = new UnavailableMethodsTraitDummyClazz();
    }

    /**
     * @param string $methodName
     *
     * @return null|mixed
     */
    public function publicCallMethodOnO2t(string $methodName): mixed
    {
        return $this->callMethodOnO2t($methodName);
    }
}
