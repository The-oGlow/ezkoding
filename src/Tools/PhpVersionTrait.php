<?php

declare(strict_types=1);

/*
 * This file is part of ezlogging
 *
 * (c) 2025 Oliver Glowa, coding.glowa.com
 *
 * This source file is subject to the Apache-2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace ollily\Tools;

trait PhpVersionTrait
{
    /**
     * Checks, if the running PHP version is greater or equal than {@link PhpVersionTrait::checkVersion}.
     *
     * @param string $checkVersion The version to check against
     *
     * @return bool TRUE=the current Version is greater or equal, else FALSE
     */
    final protected function isPhpGreater(string $checkVersion): bool
    {
        return version_compare(PHP_VERSION, $checkVersion, '>=') && !defined('HHVM_VERSION');
    }
}
