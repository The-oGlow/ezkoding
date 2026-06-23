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

use ollily\Tools\Arrays\IDoubleBackedEnum;

enum DataKeyEnum: string implements IDoubleBackedEnum
{
    // Confluence Standard
    case PAGE_ID = "pageid";
    case TITLE = "title";

    // Projectdoc Toolbox Extension
    case NAME = "name";
    case SHORT_NAME = "short-name";
    case SHORT_DESCRIPTION = "short-description";
    case DOCTYPE = "doctype";
    case TYPE = "type";

    #[\Override]
    public function intValue(): int
    {
        return (int) $this->value;
    }

    #[\Override]
    public function text(): string
    {
        return match ($this) {
            self::PAGE_ID => 'Page ID',
            self::TITLE => 'Title',
            self::NAME => 'Name',
            self::SHORT_NAME => 'Short Name',
            self::SHORT_DESCRIPTION => 'Short Description',
            self::DOCTYPE => 'Doctype',
            self::TYPE => 'Type',
            default => ''
        };
    }
}
