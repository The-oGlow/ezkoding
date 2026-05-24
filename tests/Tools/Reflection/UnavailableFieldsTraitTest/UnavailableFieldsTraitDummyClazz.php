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

/**
 * A simple clazz which will be tested by the test clazz.
 *
 * @see UnavailableFieldsTraitTestO2tClazz
 * @see UnavailableFieldsTraitTestWrongO2tClazz
 *
 * @SuppressWarnings("PHPMD.UnusedPrivateField")
 */
class UnavailableFieldsTraitDummyClazz
{
    public string $publicField = 'publicFieldValue';

    protected string $protectedField = 'protectedFieldValue';

    private string $privateField = 'privateFieldValue';
}
