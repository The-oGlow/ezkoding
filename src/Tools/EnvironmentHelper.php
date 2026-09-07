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

use Composer\Factory;
use ReflectionClass;

/**
 * Provide some common used environment variables or functions accessing environment data.
 */
class EnvironmentHelper
{
    public const string ENV_HOME_LINUX = 'HOME';

    public const string ENV_HOME_WIN = 'USERPROFILE';

    private const int ENV_OFFSET = 2;

    private const string DIR_NO = '';

    private const string DIR_PARENT = '..';

    private const string DIR_CURR = '.';

    private function __construct()
    {
        // Hide public constructor
    }

    /**
     * @param string $homeVariable Ignore parameter to use the standard settings
     *
     * @return string Full path to the home directory of the current user
     *
     * @see {@link EnvironmentHelper::ENV_HOME}
     * @see {@link EnvironmentHelper::ENV_USERPROFILE}
     */
    final public static function getHome(string $homeVariable = self::ENV_HOME_LINUX): string
    {
        $home = getenv($homeVariable);
        /** @psalm-suppress RiskyTruthyFalsyComparison */
        if ($homeVariable == self::ENV_HOME_LINUX && empty($home)) {
            // we are on windows?
            $home = getenv(self::ENV_HOME_WIN);
        }
        if (is_bool($home)) {
            $home = self::DIR_NO;
        }

        return $home;
    }

    /**
     * @return string The root folder of this php project
     *
     * @see {@link EnvironmentHelper::getComposerFilePath()}
     * @see {@link EnvironmentHelper::getProjectRootFallback()}
     */
    final public static function getProjectRoot(): string
    {
        $projectRoot = self::getComposerFilePath();
        if (empty($projectRoot)) {
            $projectRoot = EnvironmentHelper::getProjectRootFallback();
        }

        return (string)realpath($projectRoot);
    }

    /**
     * @param string $subFolder A folder below the temp folder (optional)
     *
     * @return string The system wide folder for temporarily files
     */
    final public static function getSystemTemp(string $subFolder = self::DIR_NO): string
    {
        $tmp = sys_get_temp_dir();
        if (!empty($subFolder)) {
            $tmp .= DIRECTORY_SEPARATOR . $subFolder;
        }

        return $tmp;
    }

    /**
     * Checks, if the running PHP version is greater or equal than {@link PhpVersionTrait::checkVersion}.
     *
     * @param string $checkVersion The version to check against
     *
     * @return bool TRUE=the current Version is greater or equal, else FALSE
     */
    final public static function isPhpGreater(string $checkVersion): bool
    {
        return version_compare(PHP_VERSION, $checkVersion, '>=') && !defined('HHVM_VERSION');
    }

    /**
     * @return string The full path to this composer project
     */
    public static function getComposerFilePath(): string
    {
        $composerFile = Factory::getComposerFile();
        $composerPath = (string)realpath(dirname($composerFile));
        if (self::DIR_CURR == $composerPath) {
            $composerPath = self::DIR_NO;
        }

        return $composerPath;
    }

    /**
     * @param int $folderOffset Ignore the parameter to use the default
     *
     * @return string Calculate the root folder of the project
     */
    public static function getProjectRootFallback(int $folderOffset = self::ENV_OFFSET): string
    {
        $rootClazz = new ReflectionClass(EnvironmentHelper::class);
        $rootPath  = dirname((string)realpath((string)$rootClazz->getFileName()));

        return (string)realpath($rootPath . str_repeat(DIRECTORY_SEPARATOR . self::DIR_PARENT, $folderOffset));
    }
}
