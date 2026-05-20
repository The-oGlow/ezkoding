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

use Composer\Factory;

trait EnvironmentVariableTrait
{
    /**
     * @param string $homeVariable
     *
     * @return string
     */
    final public static function getHome(string $homeVariable = 'HOME'): string
    {
        $home = getenv($homeVariable);
        /** @psalm-suppress RiskyTruthyFalsyComparison */
        if ($homeVariable == 'HOME' && empty($home)) {
            // we are on windows?
            $home = getenv('USERPROFILE');
        }
        if (is_bool($home)) {
            $home = '';
        }

        return $home;
    }

    /**
     * @return string
     */
    final public static function getProjectRoot(): string
    {
        $projectRoot = self::getComposerFilePath();
        if (empty($projectRoot)) {
            $projectRoot = self::getProjectRootFallback();
        }

        return (string)realpath($projectRoot);
    }

    /**
     * @return string
     */
    private static function getComposerFilePath(): string
    {
        $composerFile = Factory::getComposerFile();
        $composerPath = (string)realpath(dirname($composerFile));
        if ('.' == $composerPath) {
            $composerPath = '';
        }

        return $composerPath;
    }

    /**
     * @param int $folderOffset
     *
     * @return string
     */
    private static function getProjectRootFallback(int $folderOffset = 2): string
    {
        $rootClazz = new \ReflectionClass(EnvironmentVariableTrait::class);
        $rootPath  = dirname((string)realpath((string)$rootClazz->getFileName()));

        return (string)realpath($rootPath . str_repeat(DIRECTORY_SEPARATOR . '..', $folderOffset));
    }
}
