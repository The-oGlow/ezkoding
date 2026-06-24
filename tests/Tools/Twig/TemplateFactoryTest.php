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

namespace ollily\Tools\Twig;

use PHPUnit\Framework\TestCase;
use Twig\TemplateWrapper;

class TemplateFactoryTest extends TestCase
{
    public const string DEFAULT_TEMPLATE_FOLDER = 'resources/tests/Twig';

    public const string DEFAULT_TEMPLATE_NAME = 'simpleTemplate';

    public const array DEFAULT_TEMPLATE_DATA_CORRECT = [
        'FamilyName' => 'Mustermann',
        'GivenName' => 'Max',
        'Name' => '',
        'ShortName' => '',
        'ShortDescription' => 'A human named \'Max\' & Mustermann',
    ];

    protected TemplateFactory $o2t;

    #[\Override]
    public function setUp(): void
    {
        parent::setUp();
        $this->o2t = new TemplateFactory(self::DEFAULT_TEMPLATE_FOLDER);

        $expected = TemplateFactory::class;
        self::assertInstanceOf($expected, $this->o2t, 'Instance has wrong clazz-type');
    }

    public function testLoadFile(): void
    {
        $expected = TemplateWrapper::class;

        $templateName = self::DEFAULT_TEMPLATE_NAME;

        $actual = $this->o2t->loadTemplate($templateName);

        self::assertNotNull($actual, 'Template not loaded');
        self::assertInstanceOf($expected, $actual, 'Instance has wrong clazz-type');
        self::assertStringStartsWith($templateName, $actual->getTemplateName(), 'Wrong templated loaded');
    }

    public function testCreateTemplate(): void
    {
        $expected = 0;

        $templateName = self::DEFAULT_TEMPLATE_NAME;
        $templateData = self::DEFAULT_TEMPLATE_DATA_CORRECT;

        $actual = $this->o2t->createTemplate($templateName, $templateData);

        self::assertNotNull($actual, 'Null instead of rendered output returned');
        self::assertGreaterThan($expected, strlen($actual), 'Rendered output is empty');

        var_dump($actual);
    }
}
