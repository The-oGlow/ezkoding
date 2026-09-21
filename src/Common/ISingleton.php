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

use Psr\Log\LogLevel;

/**
 * Interface for a singleton.
 *
 * @author ollily
 */
interface ISingleton
{
    /** @var string Default output level */
    public const string LEVEL_DEFAULT = LogLevel::INFO;

    /**
     * Returns static access on this singletion.
     *
     * @param bool                   $withLogger TRUE=activate logging, else FALSE
     * @param int|LogLevel::*|string $level      The minimum logging level at which this handler will be triggered (Default: {@link ISingleton::LEVEL_DEFAULT})
     *
     * @return mixed Reference on this singleton
     *
     * @SuppressWarnings("PHPMD.ShortMethodName")
     */
    public static function i(bool $withLogger = true, int|LogLevel|string $level = ISingleton::LEVEL_DEFAULT): mixed;
}
