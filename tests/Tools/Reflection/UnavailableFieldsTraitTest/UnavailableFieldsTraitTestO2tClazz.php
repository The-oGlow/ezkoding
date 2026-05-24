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
class UnavailableFieldsTraitTestO2tClazz
{
    use UnavailableFieldsTrait;

    protected UnavailableFieldsTraitDummyClazz $o2t;

    public function __construct()
    {
        $this->o2t = new UnavailableFieldsTraitDummyClazz();
    }

    public function publicGetFieldFromO2t(string $fieldName): mixed
    {
        return $this->getFieldFromO2t($fieldName);
    }

    public function publicGetFieldByReflection(string $fieldName): mixed
    {
        return $this->getFieldByReflection(UnavailableFieldsTraitDummyClazz::class, $fieldName, $this->o2t);
    }

    public function publicSetFieldByReflection(string $fieldName, mixed $newValue): void
    {
        $this->setFieldByReflection(UnavailableFieldsTraitDummyClazz::class, $fieldName, $this->o2t, $newValue);
    }
}
