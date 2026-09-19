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
}
