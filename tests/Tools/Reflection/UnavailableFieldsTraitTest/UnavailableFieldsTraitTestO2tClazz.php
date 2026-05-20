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

    /** @var mixed */
    protected $o2t;

    public function __construct()
    {
        $this->o2t = new UnavailableFieldsTraitDummyClazz();
    }

    /**
     * @param string $fieldName
     *
     * @return null|mixed
     */
    public function publicGetFieldFromO2t(string $fieldName)
    {
        return $this->getFieldFromO2t($fieldName);
    }

    /**
     * @param string $fieldName
     *
     * @return null|mixed
     */
    public function publicGetFieldByReflection(string $fieldName)
    {
        return $this->getFieldByReflection(UnavailableFieldsTraitDummyClazz::class, $fieldName, $this->o2t);
    }

    /**
     * @param string     $fieldName
     * @param null|mixed $newValue
     */
    public function publicSetFieldByReflection(string $fieldName, $newValue): void
    {
        $this->setFieldByReflection(UnavailableFieldsTraitDummyClazz::class, $fieldName, $this->o2t, $newValue);
    }
}
