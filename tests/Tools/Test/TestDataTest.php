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

namespace ollily\Tools\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings("PHPMD.UnusedFormalParameter")
 */
class TestDataTest extends TestCase
{
    private static string $fileName;

    #[DataProvider('providerData')]
    public function testData(int $expectedCount, mixed $actual): void
    {
        if (is_array($actual)) {
            self::assertCount($expectedCount, $actual);
        } else {
            self::assertIsObject($actual);
        }
    }

    /**
     * @return array<mixed,mixed>
     */
    public static function providerData(): array
    {
        return [
            'oneD' => [1, TestData::DATA_OBJECT1()],
            'oneA' => [1, TestData::ARRAY_OBJECT1()],
            'twoA' => [2, TestData::ARRAY_OBJECT2()],
            'threeA' => [3, TestData::ARRAY_OBJECT3()],
        ];
    }

    public function testConstantsKey(): void
    {
        $expectedCount = 16;

        $this->verifyResult($expectedCount, 'key');
    }

    public function testConstantsData(): void
    {
        $expectedCount = 16;

        $this->verifyResult($expectedCount, 'data');
    }

    public function testConstantsArray(): void
    {
        $expectedCount = 20;

        $this->verifyResult($expectedCount, 'array');
    }

    public function verifyResult(int $expectedCount, string $pivot): void
    {
        $refClazz = new \ReflectionClass(TestData::class);

        $callback = /**
         * @param mixed $value
         * @param mixed $key
         *
         * @psalm-param mixed $value
         * @psalm-param mixed $key
         */
        function (mixed $value, mixed $key) use ($pivot): bool {
            $result = false;
            if (!is_array($key)) {
                $result = str_contains(strtolower($key), $pivot);
            }

            return $result;
        };

        $actual = array_filter($refClazz->getConstants(), $callback, 1);  // NOSONAR: php:S3011

        self::assertCount($expectedCount, $actual);
    }

    public function testPrepareTempFile(): void
    {
        self::$fileName = TestData::prepareTempFile();

        self::assertFileExists(self::$fileName);
    }

    #[Depends('testPrepareTempFile')]
    public function testCleanupTempFile(): void
    {
        TestData::cleanupTempFile(self::$fileName);

        self::assertFileDoesNotExist(self::$fileName);
    }
}
