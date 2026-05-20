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

use ollily\Tools\String\ToStringTrait;

/**
 * @phpstan-import-type TaskKey from ITaskItem
 * @phpstan-import-type TaskData from ITaskItem
 */
class TaskItem implements ITaskItem
{
    use ToStringTrait;

    /**
     * @var mixed
     *
     * @phpstan-var TaskKey */
    private $key;

    /**
     * @var array
     *
     * @phpstan-var TaskData
     */
    private $data;

    /**
     * @param mixed $key
     * @param array $data
     *
     * @phpstan-param TaskKey  $key
     * @phpstan-param TaskData $data
     */
    public function __construct($key, array $data)
    {
        $this->key  = $key;
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    protected function __toStringValues() // NOSONAR: php:S100
    {
        return $this->data;
    }
}
