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

namespace PHPUnit\Framework\ConstantCheckTestCaseTest;

/**
 * A simple clazz which will be tested by the test clazz.
 *
 * @see ConstantCheckTestCaseClazz
 */
class ConstantCheckTestCaseDummyClazz
{
    public const    TEST_CLAZZ                               = self::class . '::';

    public const    TEST_CONST_PREFIX                        = self::TEST_CLAZZ . 'TEST_CONST';

    public const    TEST_CONST_ARRAY                         = ['one', 'two'];

    public const    TEST_CONST_PUBLIC    = 'public';

    protected const TEST_CONST_PROTECTED = 'protected';

    private const   TEST_CONST_PRIVATE   = 'private';
}
