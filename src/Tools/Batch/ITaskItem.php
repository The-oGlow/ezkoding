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

/**
 * @phpstan-type TaskKey mixed
 * @phpstan-type TaskData array<mixed,mixed>
 */
interface ITaskItem
{
    /**
     * @return mixed
     *
     * @phpstan-return TaskKey
     */
    public function getKey();

    /**
     * @return array
     *
     * @phpstan-return TaskData
     */
    public function getData(): array;

    /**
     * @return mixed
     */
    public function __toString();
}
