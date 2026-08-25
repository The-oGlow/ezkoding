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
    case PAGE_ID = "Page Id";
    case TITLE = "Title";

    // Projectdoc Toolbox Extension
    case NAME = "Name";
    case SHORT_NAME = "Short Name";
    case SHORT_DESCRIPTION = "Short Description";
    case DOCTYPE = "Doctype";
    case TYPE = "Type";

    #[\Override]
    public function intValue(): int
    {
        return 0;
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
            self::TYPE => 'Type'
        };
    }

    #[\Override]
    public function objectValue(): mixed
    {
        return null;
    }
}
