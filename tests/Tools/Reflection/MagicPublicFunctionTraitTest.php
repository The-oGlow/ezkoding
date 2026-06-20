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

use ollily\Tools\Test\TestData;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MagicPublicFunctionTraitTest extends TestCase
{
    private MagicPublicFunctionTraitDummyClazz $o2t;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->o2t = new class () extends MagicPublicFunctionTraitDummyClazz {
            #[\Override]
            public function abstractFunction(): string
            {
                return MagicPublicFunctionTraitDummyClazz::ABSTRACT;
            }
        };
    }

    public function testCallPublicMethod(): void
    {
        $expected = MagicPublicFunctionTraitDummyClazz::PUBLIC;

        $actual = $this->o2t->publicFunction();

        self::assertEquals($expected, $actual);
    }

    public function testCallPublicMethodWithArgs(): void
    {
        $arg1 = TestData::DATA_NUM2;
        $arg2 = TestData::DATA_ALPHA1;

        $expected = sprintf(MagicPublicFunctionTraitDummyClazz::MSG, MagicPublicFunctionTraitDummyClazz::PUBLIC, $arg1, $arg2);

        $actual = $this->o2t->publicFunctionWithArgs($arg1, $arg2);

        self::assertEquals($expected, $actual);
    }

    public function testCallPublicMethodWithWrongArgs(): void
    {
        $arg1 = TestData::DATA_NUM2;
        $arg2 = TestData::DATA_ALPHA1;

        $this->expectException(\TypeError::class);
        /** @psalm-suppress InvalidArgument
         * @phpstan-ignore argument.type,argument.type */
        $this->o2t->publicFunctionWithArgs($arg2, $arg1);
    }

    public function testCallDynamicallyWithWrongArgs(): void
    {
        $arg1 = TestData::DATA_NUM2;
        $arg2 = TestData::DATA_ALPHA1;
        $methodName = 'publicFunctionWithArgs';

        $this->expectException(\TypeError::class);
        /** @phpstan-ignore argument.type,argument.type */
        $this->o2t->$methodName($arg2, $arg1);
    }

    public function testCallProtectedMethod(): void
    {
        $expected = TestData::DATA_NULL;

        /** @psalm-suppress UndefinedMagicMethod
         *  @phpstan-ignore method.protected */
        $actual = $this->o2t->protectedFunction();

        self::assertEquals($expected, $actual);
    }

    public function testCallPrivateMethod(): void
    {
        $expected = TestData::DATA_NULL;

        /** @psalm-suppress UndefinedMagicMethod
         * @phpstan-ignore method.private */
        $actual = $this->o2t->privateFunction();

        self::assertEquals($expected, $actual);
    }

    public function testCallAbstractPublicMethod(): void
    {
        $expected = MagicPublicFunctionTraitDummyClazz::ABSTRACT;

        $actual = $this->o2t->abstractFunction();

        self::assertEquals($expected, $actual);
    }

    /**
     * @param mixed  $expected
     * @param string $methodName
     * @param mixed  $arg1
     * @param mixed  $arg2
     */
    #[DataProvider('provideMethodNames')]
    public function testCallDynamically(mixed $expected, string $methodName, mixed $arg1 = null, mixed $arg2 = null): void
    {
        if (!is_null($arg1)) {
            if (is_null($arg2)) {
                $actual = $this->o2t->$methodName($arg1);
            } else {
                $actual = $this->o2t->$methodName($arg1, $arg2);
            }
        } else {
            $actual = $this->o2t->$methodName();
        }
        self::assertEquals($expected, $actual);
    }

    /**
     * @return array<mixed,mixed>
     */
    public static function provideMethodNames(): array
    {
        return [
            MagicPublicFunctionTraitDummyClazz::MN_PUBLIC => [MagicPublicFunctionTraitDummyClazz::PUBLIC, MagicPublicFunctionTraitDummyClazz::MN_PUBLIC],
            MagicPublicFunctionTraitDummyClazz::MN_PROTECTED => [TestData::DATA_NULL, MagicPublicFunctionTraitDummyClazz::MN_PROTECTED],
            MagicPublicFunctionTraitDummyClazz::MN_PRIVATE => [TestData::DATA_NULL, MagicPublicFunctionTraitDummyClazz::MN_PRIVATE],
            MagicPublicFunctionTraitDummyClazz::MN_ABSTRACT => [MagicPublicFunctionTraitDummyClazz::ABSTRACT, MagicPublicFunctionTraitDummyClazz::MN_ABSTRACT],
            MagicPublicFunctionTraitDummyClazz::MN_PUBLIC_WITH_ARGS => [
                sprintf(MagicPublicFunctionTraitDummyClazz::MSG, MagicPublicFunctionTraitDummyClazz::PUBLIC, TestData::DATA_NUM2, TestData::DATA_ALPHA1),
                MagicPublicFunctionTraitDummyClazz::MN_PUBLIC_WITH_ARGS, TestData::DATA_NUM2, TestData::DATA_ALPHA1],
            TestData::NOTEXIST_NAME => [TestData::DATA_NULL, TestData::NOTEXIST_NAME],
        ];
    }
}
