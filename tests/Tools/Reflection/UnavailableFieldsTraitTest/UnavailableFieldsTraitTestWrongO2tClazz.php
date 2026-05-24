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

namespace ollily\Tools\Reflection\UnavailableFieldsTraitTest;

use ollily\Tools\Reflection\UnavailableFieldsTrait;

/**
 * This is the test clazz which will be tested.
 *
 * @see  UnavailableFieldsTraitDummyClazz
 * @see  UnavailableFieldsTraitTest
 */
class UnavailableFieldsTraitTestWrongO2tClazz
{
    use UnavailableFieldsTrait;

    private UnavailableFieldsTraitDummyClazz $wrongO2t;

    public function __construct()
    {
        $this->wrongO2t = new UnavailableFieldsTraitDummyClazz();
    }

    public function publicGetFieldFromO2t(string $fieldName): mixed
    {
        return $this->getFieldFromO2t($fieldName);
    }
}
