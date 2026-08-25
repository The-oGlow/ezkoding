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

namespace ollily\Tools\Batch;

use Ds\Map;
use Ds\Set;

/**
 * @phpstan-type TTaskItemId mixed
 * @phpstan-type TDataKey mixed
 * @phpstan-type TDataValue mixed
 */
interface ITaskItem extends \Stringable
{
    /**
     * return mixed.
     *
     * @phpstan-return TTaskItemId
     */
    public function getItemId(): mixed;

    /**
     * return Map<mixed,mixed>.
     *
     * @phpstan-return Map<TDataKey,TDataValue>
     */
    public function getData(): Map;

    /**
     * return Set<mixed>.
     *
     * @phpstan-return Set<TDataKey>
     */
    public function getDataKeys(): Set;

    /**
     * @param mixed $dataKey
     *
     * @phpstan-param TDataKey $dataKey
     *
     * @return mixed
     *
     * @phpstan-return TDataValue
     */
    public function getDataValue(mixed $dataKey): mixed;

    /**
     * @param mixed $dataKey
     *
     * @phpstan-param TDataKey $dataKey
     *
     * @return bool TRUE=Item is empty, else false
     */
    public function isDataKeyExist(mixed $dataKey): bool;

    /**
     * @return bool TRUE=Item is empty, else false
     */
    public function empty(): bool;

    /**
     * @return int Count of columns
     */
    public function count(): int;

    #[\Override]
    public function __toString(): string;
}
