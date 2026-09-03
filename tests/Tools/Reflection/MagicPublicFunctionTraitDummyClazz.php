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

namespace ollily\Tools\Reflection;

abstract class MagicPublicFunctionTraitDummyClazz
{
    use MagicPublicFunctionTrait;

    public const string MSG = '%s with %s,%s';

    public const string MN_PUBLIC = 'publicFunction';

    public const string MN_PUBLIC_WITH_ARGS = 'publicFunctionWithArgs';

    public const string MN_PROTECTED = 'protectedFunction';

    public const string MN_PRIVATE = 'privateFunction';

    public const string MN_ABSTRACT = 'abstractFunction';

    public const string PUBLIC = 'public';

    public const string PROTECTED = 'protected';

    public const string PRIVATE = 'private';

    public const string ABSTRACT = 'abstract';

    abstract public function abstractFunction(): string;

    public function publicFunction(): string
    {
        return self::PUBLIC;
    }

    public function publicFunctionWithArgs(int $arg1, string $arg2): string
    {
        return sprintf(self::MSG, self::PUBLIC, $arg1, $arg2);
    }

    protected function protectedFunction(): string
    {
        return self::PROTECTED;
    }

    /** @SuppressWarnings("PHPMD.UnusedPrivateMethod") */
    private function privateFunction(): string
    {
        return self::PRIVATE;
    }
}
