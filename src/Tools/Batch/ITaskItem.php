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
     * @return TTaskItemId
     */
    public function getItemId(): mixed;

    /**
     * @return Map<TDataKey,TDataValue>
     */
    public function getData(): Map;

    /**
     * return Set<TDataKey>.
     *
     * @phpstan-ignore missingType.generics
     */
    public function getDataKeys(): Set;

    /**
     * @param TDataKey $dataKey
     *
     * @return TDataValue
     */
    public function getDataValue(mixed $dataKey): mixed;

    /**
     * @param TDataKey $dataKey
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
