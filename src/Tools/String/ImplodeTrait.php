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

namespace ollily\Tools\String;

use ArrayAccess;
use Stringable;

trait ImplodeTrait
{
    /**
     * @return string Default glue character
     *
     * @see ImplodeTrait::array_flatten()
     * @see ImplodeTrait::implode_recursive()
     *
     * TODO: Make it a constant with PHP 8
     */
    final public function DEFAULT_GLUE(): string
    {
        return ',';
    }

    /**
     * @return string Default separate character for csv
     *
     * @see ImplodeTrait::array_flatten()
     * @see ImplodeTrait::implode_recursive()
     *
     * TODO: Make it a constant with PHP 8
     */
    final public function DEFAULT_ITEM_SEP(): string
    {
        return ';';
    }

    /**
     * Recursively implodes an array with optional key inclusion.
     *
     * @param string $glue        value that glues elements together
     * @param mixed  $anyData     multi-dimensional array to recursively implode
     * @param bool   $withTextSep add a text seperator (") around each value of a scalar type (default: false)
     * @param bool   $withKeys    include keysForValue before their values (default: false)
     *
     * @return string imploded array
     *
     * @see https://www.php.net/manual/en/language.types.type-system.php
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     */
    // @phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    final public function implode_recursive(string $glue, $anyData, bool $withTextSep = false, bool $withKeys = false): string // NOSONAR: php:S100
    {
        $output   = '';
        $valueIdx = 0;
        $textSep  = $withTextSep ? '"' : '';
        $objWithArray = (is_object($anyData) && is_subclass_of($anyData, ArrayAccess::class));

        if (is_array($anyData) || $objWithArray) {
            foreach ($anyData as $key => $value) { // @phpstan-ignore foreach.nonIterable
                $currKey = ($withKeys ? (is_int($key) ? $key : "'$key'") . '=>' : ''); // NOSONAR: php:S3358
                $output .= ($valueIdx > 0 ? $glue : '') .  $currKey;

                if (is_array($value)) {
                    $arrOutput = $this->implode_recursive($glue, $value, $withTextSep, $withKeys);
                    if (!empty($arrOutput)) {
                        $output .= '[' . $arrOutput . ']';
                    } else {
                        $output .= '[]';
                    }
                } else {
                    if (is_object($value)) {
                        $objOutput = $this->implode_recursive($glue, $value, $withTextSep, $withKeys);
                        if (!empty($objOutput)) {
                            $output .= '{' . $objOutput . '}';
                        } else {
                            $output .= '{}';
                        }
                    } else {
                        $output .= $textSep . ((string) $value) . $textSep;
                    }
                }
                $valueIdx++;
            }
        } else {
            if (is_object($anyData)) {
                if ($anyData instanceof Stringable) {
                    $output = $anyData->__toString();
                } else {
                    $output = get_class($anyData);
                }
            } else {
                $output = $anyData;
            }
        }

        return $output;
    }

    /**
     * Flatten a multidimensional anyData to one dimension, optionally preserving keys.
     * Original found on {@link https://stackoverflow.com/a/526633}.
     *
     * @param array<mixed,mixed> $anyData      the anyData to flatten
     * @param int                $preserveKeys 0 to not preserve keys (default),
     *                                         1 to preserve string keys only,
     *                                         2 to preserve all keys
     * @param array<mixed,mixed> $output       internal use argument for recursion
     *
     * @return array<mixed,mixed>
     *
     * @see https://stackoverflow.com/a/526633
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     */
    // @phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    final public function array_flatten(array $anyData, int $preserveKeys = 0, array &$output = []): array // NOSONAR: php:S100
    {
        foreach ($anyData as $anyKey => $anyValue) {
            if (is_array($anyValue)) {
                $output = $this->array_flatten($anyValue, $preserveKeys, $output);
            } elseif ($preserveKeys + (int)is_string($anyKey) > 1) {
                $output[$anyKey] = $anyValue;
            } else {
                $output[] = $anyValue;
            }
        }

        return $output;
    }
}
