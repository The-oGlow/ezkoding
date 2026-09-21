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

use ollily\Tools\Test\TestData as TeDa;

/**
 * @author ollily
 */
class AbstractContainerTestDummyClazz extends AbstractContainer
{
    public const string TEST_MODE_1 = TeDa::KEY_ALPHA1;

    public const int TEST_MODE_2 = TeDa::KEY_NUM5;

    /**
     * @inheritDoc
     */
    #[\Override]
    protected function prepareModes(): void
    {
        $this->setModes([self::TEST_MODE_1,self::TEST_MODE_2]);
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    protected function prepareData(): void
    {
        $allData                     = [];
        $allData[self::TEST_MODE_1] = [];
        $allData[self::TEST_MODE_1][TeDa::KEY_NUM1]                     = [TeDa::DATA_ALPHA1];
        $allData[self::TEST_MODE_1][TeDa::KEY_NUM2]                     = [TeDa::DATA_ALPHA2];
        $allData[self::TEST_MODE_2] = [];

        $this->setAllData($allData);
    }

    // Set method to public for testing purpose

    /**
     * @return array<mixed>
     */
    public function publicToStringValues(): array
    {
        return parent::__toStringValues();
    }
}
