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

namespace ollily\Common;

use Monolog\EasyGoingLogger;
use ollily\Tools\String\ToStringTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Abstract implementation for a container clazz.
 *
 * @author ollily
 */
abstract class AbstractContainer implements IContainer
{
    use ToStringTrait;

    /** @var int The limit, how many items of a arrays are logged to a logger */
    private const int ITEM_MAX = 20;

    /** @var array<mixed> Stored data */
    private array $data = [];

    /** @var int[]|string[] The modes how to access the data */
    private array $modes = [];

    private static LoggerInterface $logger;

    /**
     * Define the valid modes to access the data.
     */
    abstract protected function prepareModes(): void;

    /**
     * Initialize the data.
     */
    abstract protected function prepareData(): void;

    /**
     * Public constructor.
     *
     * @param int|LogLevel::*|string $level The minimum logging level at which this handler will be triggered (Default: {@link IContainer::LEVEL_DEFAULT})
     */
    public function __construct(int|LogLevel|string $level = IContainer::LEVEL_DEFAULT)
    {
        self::$logger = EasyGoingLogger::init(AbstractSingleton::class, level: $level);
        self::$logger->debug('START');

        $this->prepareModes();
        $this->prepareData();

        self::$logger->debug('END');
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function getAllData(): array
    {
        return $this->data;
    }

    /**
     * Set the complete data.
     *
     * @param array<mixed> $allData Array of stored data
     */
    protected function setAllData(array $allData): void
    {
        $this->data = $allData;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function getKeys(): array
    {
        return array_keys($this->getAllData());
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function keyExists(mixed $key): bool
    {
        return !empty($key) && array_key_exists($key, $this->getAllData());
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function getModes(): array
    {
        return $this->modes;
    }

    /**
     * Set all the valid modes.
     *
     * @param int[]|string[] $modes The modes how to access the data
     */
    protected function setModes(array $modes): void
    {
        $this->modes = $modes;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function getDataByMode(string|int $mode): mixed
    {
        self::$logger->debug('START', [$mode]);

        $value = [];
        if ($this->keyExists($mode)) {
            $value = $this->getAllData()[$mode];
            if (is_array($value) && count($value) > self::ITEM_MAX) {
                self::$logger->debug('count   :', [count($value)]);
            } else {
                self::$logger->debug('elements:', [$value]);
            }
        } else {
            self::$logger->warning('Mode not found', [$mode]);
        }

        self::$logger->debug('END');

        return $value;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    protected function __toStringValues(): mixed
    {
        return $this->getAllData();
    }
}
