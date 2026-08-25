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

namespace ollily\Tools;

/**
 * Provide some common used environment variables.
 */
trait EnvironmentVariableTrait
{
    public const string ENV_HOME = 'HOME';

    public const string ENV_USERPROFILE = 'USERPROFILE';

    public const int ENV_OFFSET = 2;

    /**
     * @param string $homeVariable Ignore parameter to use the standard settings
     *
     * @return string Full path to the home directory of the current user
     *
     * @see {@link EnvironmentVariableTrait::ENV_HOME}
     * @see {@link EnvironmentVariableTrait::ENV_USERPROFILE}
     */
    final public static function getHome(string $homeVariable = self::ENV_HOME): string
    {
        $home = getenv($homeVariable);
        /** @psalm-suppress RiskyTruthyFalsyComparison */
        if ($homeVariable == self::ENV_HOME && empty($home)) {
            // we are on windows?
            $home = getenv(self::ENV_USERPROFILE);
        }
        if (is_bool($home)) {
            $home = '';
        }

        return $home;
    }

    /**
     * @return string The root folder of this php project
     *
     * @see {@link EnvironmentVariableTrait::getComposerFilePath()}
     * @see {@link EnvironmentVariableTrait::getProjectRootFallback()}
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
     * @param string $subFolder A folder below the temp folder (optional)
     *
     * @return string The system wide folder for temporarily files
     */
    final public static function getSystemTemp(string $subFolder = ''): string
    {
        $tmp = sys_get_temp_dir();
        if (!empty($subFolder)) {
            $tmp .= DIRECTORY_SEPARATOR . $subFolder;
        }

        return $tmp;
    }

    /**
     * @return string The full path to this composer project
     */
    private static function getComposerFilePath(): string
    {
        $composerFile = \Composer\Factory::getComposerFile();
        $composerPath = (string)realpath(dirname($composerFile));
        if ('.' == $composerPath) {
            $composerPath = '';
        }

        return $composerPath;
    }

    /**
     * @param int $folderOffset Ignore the parameter to use the default
     *
     * @return string Calculate the root folder of the project
     */
    private static function getProjectRootFallback(int $folderOffset = self::ENV_OFFSET): string
    {
        $rootClazz = new \ReflectionClass(EnvironmentVariableTrait::class);
        $rootPath  = dirname((string)realpath((string)$rootClazz->getFileName()));

        return (string)realpath($rootPath . str_repeat(DIRECTORY_SEPARATOR . '..', $folderOffset));
    }
}
