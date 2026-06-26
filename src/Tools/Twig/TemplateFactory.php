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

use Exception;
use Monolog\EasyGoingLogger;
use ollily\Tools\Emergency;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;
use Twig\TemplateWrapper;
use ollily\Tools\EnvironmentVariableTrait;
class TemplateFactory
{
    use EnvironmentVariableTrait;
    
    public const string DEFAULT_TEMPLATE_FOLDER = 'resources/src/Twig';

    public const string DEFAULT_TEMPLATE_EXT = '.html.twig';

    public const string DEFAULT_CACHE_FOLDER = 'twigcache';
    
    private static LoggerInterface $logger;

    private LoaderInterface $loader;

    private Environment $environment;

    /**
     * @param string $templatePath
     * @param string $cachePath
     */
    public function __construct(string $templatePath, string $cachePath = '')
    {
        self::$logger = EasyGoingLogger::init(TemplateFactory::class);
        if (empty($cachePath)) {
            $cachePath= self::getSystemTemp(self::DEFAULT_CACHE_FOLDER);
        }
        [$templatePath, $cachePath] = $this->verifyConfig($templatePath, $cachePath);

        try {
            $this->loader = new FilesystemLoader($templatePath);
            $this->environment = new Environment($this->loader, [
                'cache' => $cachePath,
                'debug' => true,
                'auto_reload' => true,
                'strict_variables' => false,
                'autoescape' => 'html',
            ]);
        } catch (Error $exc) {
            Emergency::exceptionStop($exc);
        } catch (Exception $exc) {
            Emergency::exceptionStop($exc);
        }
    }

    /**
     * @param string             $templateName
     * @param array<mixed,mixed> $templateData
     *
     * @return string
     */
    public function renderTemplate(string $templateName, array $templateData): string
    {
        self::$logger->debug('START - templateName, count(templateData)', [$templateName, count($templateData)]);
        $templateRendered = '';

        $template = $this->loadTemplate($templateName);
        if (!empty($template)) {
            $templateData = $this->cleanTemplateData($templateData);

            try {
                $templateRendered = $template->render($templateData);
            } catch (Error $exc) {
                self::$logger->error('Cannot render: ' . $exc->getMessage());
            }
        }

        self::$logger->debug('template size', [strlen($templateRendered)]);
        self::$logger->debug('END');

        return $templateRendered;
    }

    /**
     * @param string $templateName
     *
     * @return null|TemplateWrapper
     */
    public function loadTemplate(string $templateName): mixed
    {
        $template = null;

        if (!str_ends_with($templateName, self::DEFAULT_TEMPLATE_EXT)) {
            $templateName .= self::DEFAULT_TEMPLATE_EXT;
        }
        self::$logger->debug('loading template', [$templateName]);

        try {
            $template = $this->environment->load($templateName);
        } catch (Error $exc) {
            self::$logger->error('Cannot load: ' . $exc->getMessage());
        }

        return $template;
    }

    /**
     * @param string $templatePath
     * @param string $cachePath
     *
     * @return array<mixed,mixed>
     */
    protected function verifyConfig(string $templatePath, string $cachePath): array
    {
        if (empty($templatePath)) {
            $templatePath = self::DEFAULT_TEMPLATE_FOLDER;
        }
        $templatePath = realpath($templatePath);

        if (empty($cachePath)) {
            $cachePath = 'false';
        } else {
            if (!file_exists($cachePath)){
                $result = mkdir(directory:$cachePath, recursive: true);
            }
            $cachePath = realpath($cachePath);
        }

        self::$logger->debug('Template path', [$templatePath]);
        self::$logger->debug('Cache path ', [$cachePath]);

        return [$templatePath, $cachePath];
    }

    /**
     * @param array<mixed,mixed> $templateData
     *
     * @return array<mixed,mixed>
     */
    protected function cleanTemplateData(array $templateData): array
    {
        $callback = function (mixed $key): mixed {
            return str_replace(' ', '', $key);
        };
        /** @var array<string,string>
         * @phpstan-ignore varTag.type */
        $fixedKeys = array_map($callback, array_keys($templateData));

        return array_combine($fixedKeys, array_values($templateData));
    }
}
