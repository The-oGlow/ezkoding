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

use PHPUnit\Framework\TestCase;

/**
 * This is the test clazz which will test the test clazz.
 *
 * @see UnavailableFieldsTraitTestO2tClazz
 * @see UnavailableFieldsTraitTestWrongO2tClazz
 */
class UnavailableFieldsTraitTest extends TestCase
{
    /** @var UnavailableFieldsTraitTestO2tClazz */
    protected $o2t;

    /** @var string[] */
    private $fieldNames = ['publicField', 'protectedField', 'privateField'];

    public function setUp(): void
    {
        parent::setUp();
        $this->o2t = new UnavailableFieldsTraitTestO2tClazz();
    }

    public function testGetFieldByReflection(): void
    {
        foreach ($this->fieldNames as $fieldName) {
            self::assertEquals($fieldName . 'Value', $this->o2t->publicGetFieldByReflection($fieldName));
        }
    }

    public function testSetFieldByReflection(): void
    {
        foreach ($this->fieldNames as $fieldName) {
            self::assertEquals($fieldName . 'Value', $this->o2t->publicGetFieldByReflection($fieldName));

            $newValue = $fieldName . 'newValue';
            $this->o2t->publicSetFieldByReflection($fieldName, $newValue);
            self::assertEquals($newValue, $this->o2t->publicGetFieldByReflection($fieldName));
        }
    }

    public function testGetFieldFromO2t(): void
    {
        foreach ($this->fieldNames as $fieldName) {
            self::assertEquals($fieldName . 'Value', $this->o2t->publicGetFieldFromO2t($fieldName));
        }
    }

    public function testGetFieldFromO2tReturnNull(): void
    {
        /** @var UnavailableFieldsTraitTestWrongO2tClazz $o2tb */
        $o2tb = new UnavailableFieldsTraitTestWrongO2tClazz();
        foreach ($this->fieldNames as $fieldName) {
            self::assertNull($o2tb->publicGetFieldFromO2t($fieldName));
        }
    }
}
