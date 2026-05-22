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

namespace ollily\Tools\Test;

/**
 * Providing useful data for the use in test cases.
 *
 * @SuppressWarnings("PHPMD.CamelCasePropertyName")
 * @SuppressWarnings("PHPMD.CamelCaseMethodName")
 */
class TestData
{
    // Unspecific test data

    // Single data
    public const DATA_NUM1 = 11;

    public const DATA_NUM2 = 22;

    public const DATA_NUM3 = 33;

    public const DATA_NUM4 = 44;

    public const DATA_NUM5 = 55;

    public const DATA_ALPHA1 = 'DATA-ALPHA11';

    public const DATA_ALPHA2 = 'DATA-ALPHA22';

    public const DATA_ALPHA3 = 'DATA-ALPHA33';

    public const DATA_ALPHA4 = 'DATA-ALPHA44';

    public const DATA_ALPHA5 = 'DATA-ALPHA55';

    public const DATA_BOOL_T = true;

    public const DATA_BOOL_F = false;

    /** @var null|string */
    public const DATA_NULL = null;

    public const DATA_EMPTY = '';

    public const DATA_INVALID = 'INVALID';

    public const DATA_NOTEXIST = 'NOT-EXIST';

    // Misc Data

    public const NOTEXIST_NAME = 'XXXName';

    public const NOTEXIST_ID = 99999;

    public const NOTEXIST_ARRAY = [self::NOTEXIST_ID => self::NOTEXIST_NAME];

    public const NOTEXIST_CLAZZ = 'ollily/NotExistClazz';

    public const NOTEXIST_FILE = DIRECTORY_SEPARATOR . 'NotExistFile';

    // Array Data

    public const ARRAY_ITEM_SEP = ',';

    // Array keys
    public const KEY_NUM1 = 1;

    public const KEY_NUM2 = 2;

    public const KEY_NUM3 = 3;

    public const KEY_NUM4 = 4;

    public const KEY_NUM5 = 5;

    public const KEY_ALPHA1 = 'KEY-ALPHA1';

    public const KEY_ALPHA2 = 'KEY-ALPHA2';

    public const KEY_ALPHA3 = 'KEY-ALPHA3';

    // Arrays complete

    public const ARRAY_EMPTY = [];

    /** @var null|array<mixed,mixed> */
    public const ARRAY_NULL = null;

    // Arrays with alphanumeric data

    public const ARRAY_ALPHA1 = [self::DATA_ALPHA1];

    public const ARRAY_ALPHA2 = [self::DATA_ALPHA1, self::DATA_ALPHA2];

    public const ARRAY_ALPHA3 = [self::DATA_ALPHA1, self::DATA_ALPHA2, self::DATA_ALPHA3];

    public const ARRAY_ALPHA4 = [self::DATA_ALPHA1, self::DATA_ALPHA2, self::DATA_ALPHA3, self::DATA_ALPHA4];

    public const ARRAY_ALPHA5 = [self::DATA_ALPHA1, self::DATA_ALPHA2, self::DATA_ALPHA3, self::DATA_ALPHA4, self::DATA_ALPHA5];

    // Arrays with numeric data

    public const ARRAY_NUM1 = [self::DATA_NUM1];

    public const ARRAY_NUM2 = [self::DATA_NUM1, self::DATA_NUM2];

    public const ARRAY_NUM3 = [self::DATA_NUM1, self::DATA_NUM2, self::DATA_NUM3];

    // Arrays with bool data

    public const ARRAY_BOOL1 = [self::DATA_BOOL_T];

    public const ARRAY_BOOL2 = [self::DATA_BOOL_T, self::DATA_BOOL_F];

    // Arrays with alphanumeric key

    public const ARRAY_ALPHA_KEY1 = [self::KEY_ALPHA1 => self::DATA_ALPHA1];

    public const ARRAY_ALPHA_KEY2 = [
        self::KEY_ALPHA1 => self::DATA_ALPHA1,
        self::KEY_ALPHA2 => self::DATA_ALPHA2
    ];

    public const ARRAY_ALPHA_KEY3 = [
        self::KEY_ALPHA1 => self::DATA_ALPHA1,
        self::KEY_ALPHA2 => self::DATA_ALPHA2,
        self::KEY_ALPHA3 => self::DATA_ALPHA3
    ];

    // Arrays with exlicit numeric key

    public const ARRAY_NUM_KEY1 = [self::KEY_NUM1 => self::DATA_NUM1];

    public const ARRAY_NUM_KEY2 = [
        self::KEY_NUM1 => self::DATA_NUM1,
        self::KEY_NUM2 => self::DATA_NUM2
    ];

    public const ARRAY_NUM_KEY3 = [
        self::KEY_NUM1 => self::DATA_NUM1,
        self::KEY_NUM2 => self::DATA_NUM2,
        self::KEY_NUM3 => self::DATA_NUM3
    ];

    // Arrays with object data

    /**
     * @see self::C_ARRAY_OBJECT1()
     *
     * @var array<mixed>
     */
    private static $ARRAY_OBJECT1 = []; // NOSONAR: php:S100

    /**
     * @see self::C_ARRAY_OBJECT2()
     *
     * @var array<mixed>
     */
    private static $ARRAY_OBJECT2 = []; // NOSONAR: php:S100

    /**
     * @see self::C_ARRAY_OBJECT3()
     *
     * @var array<mixed>
     */
    private static $ARRAY_OBJECT3 = []; // NOSONAR: php:S100

    // Filesystem Data

    // Foldernames

    /** @var null|string */
    public const FILE_FOLDERNAME_NULL = null;

    public const FILE_FOLDERNAME_EMPTY = '';

    public const FILE_FOLDERNAME = 'FOLDER-EXIST';

    // Filenames

    /** @var null|string */
    public const FILE_FILENAME_NULL = null;

    public const FILE_FILENAME_EMPTY = '';

    public const FILE_FILENAME = 'FILE-EXIST';

    public const FILE_FILENAME_PREFIX = 'teda-';

    // File Extensions

    /** @var null|string */
    public const FILE_EXT_NULL = null;

    public const FILE_EXT_EMPTY = '';

    public const FILE_EXT_NAME = 'EXT-EXIST';

    public const FILE_EXT_PHP = '.php';

    public const FILE_EXT_TXT = '.txt';

    public const FILE_EXT_JSON = '.json';

    public const FILE_EXT_CSV = '.csv';

    // Static functions

    // Misc Data

    public static function DATA_OBJECT1(): TestDataFoo // NOSONAR: php:S100
    {
        return TestDataFoo::init(TestData::DATA_NUM1);
    }

    /**
     * @return array<mixed>
     */
    public static function ARRAY_OBJECT1(): array // NOSONAR: php:S100
    {
        if (empty(self::$ARRAY_OBJECT1)) {
            self::$ARRAY_OBJECT1 = [self::KEY_ALPHA1 => TestDataFoo::init(self::DATA_NUM1)];
        }

        return self::$ARRAY_OBJECT1;
    }

    /**
     * @return array<mixed>
     */
    public static function ARRAY_OBJECT2(): array // NOSONAR: php:S100
    {
        if (empty(self::$ARRAY_OBJECT2)) {
            self::$ARRAY_OBJECT2 = [
                self::KEY_ALPHA1 => TestDataFoo::init(self::DATA_NUM1),
                self::KEY_ALPHA2 => TestDataFoo::init(self::DATA_NUM2),
            ];
        }

        return self::$ARRAY_OBJECT2;
    }

    /**
     * @return array<mixed>
     */
    public static function ARRAY_OBJECT3(): array // NOSONAR: php:S100
    {
        if (empty(self::$ARRAY_OBJECT3)) {
            self::$ARRAY_OBJECT3 = [
                self::KEY_ALPHA1 => TestDataFoo::init(self::DATA_NUM1),
                self::KEY_ALPHA2 => TestDataFoo::init(self::DATA_NUM2),
                self::KEY_ALPHA3 => TestDataFoo::init(self::DATA_NUM3),
            ];
        }

        return self::$ARRAY_OBJECT3;
    }

    private function __construct()
    {
        // Hide the constructor
    }

    // Misc Functions

    /**
     * @param string $prefix A prefix for the filename
     *
     * @return string Full filename for a temporary file
     *
     * @see TestData::FILE_FILENAME_PREFIX
     */
    public static function prepareTempFile(string $prefix = self::FILE_FILENAME_PREFIX): string
    {
        return tempnam(sys_get_temp_dir(), $prefix);
    }

    /**
     * @param string $fileName Full filename for a temporary file
     */
    public static function cleanupTempFile(string $fileName): void
    {
        try {
            if (!empty($fileName) && file_exists($fileName)) {
                unlink($fileName);
            }
        } catch (\Throwable $ex) {
            echo "\n[WARNING] $ex";
        }
    }
}
