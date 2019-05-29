<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton\Partials;

use DrdPlus\CalculatorSkeleton\CalculatorApplication;
use DrdPlus\CalculatorSkeleton\CalculatorConfiguration;
use DrdPlus\CalculatorSkeleton\CalculatorServicesContainer;
use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\RulesSkeleton\Dirs;
use DrdPlus\RulesSkeleton\Environment;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\ServicesContainer;

/**
 * @method Dirs getDirs
 * @method Dirs createDirs(string $projectRoot)
 * @method CalculatorConfiguration getConfiguration
 * @method CalculatorServicesContainer getServicesContainer
 * @method TestsConfigurationReader getTestsConfiguration
 * @method static assertTrue($value, $message = '')
 * @method static assertFalse($value, $message = '')
 * @method static assertSame($expected, $actual, $message = '')
 * @method static assertNotSame($expected, $actual, $message = '')
 * @method static assertNotEmpty($value, $message = '')
 * @method static fail($message)
 */
trait CalculatorTestTrait
{

    protected function isSkeletonChecked(string $skeletonDocumentRoot = null): bool
    {
        $documentRootRealPath = \realpath($this->getProjectRoot());
        self::assertNotEmpty($documentRootRealPath, 'Can not find out real path of document root ' . \var_export($this->getProjectRoot(), true));
        $skeletonRootRealPath = \realpath($skeletonDocumentRoot ?? __DIR__ . '/../../../..');
        self::assertNotEmpty($skeletonRootRealPath, 'Can not find out real path of skeleton root ' . \var_export($skeletonRootRealPath, true));
        self::assertContains(
            \basename($skeletonRootRealPath),
            ['calculator-skeleton', 'kalkulator.skeleton'],
            'Expected different trailing dir of skeleton document root'
        );

        return $documentRootRealPath === $skeletonRootRealPath;
    }

    /**
     * @param Dirs $dirs = NULL,
     * @param bool $inForcedProductionMode = false,
     * @param bool $inDevMode = false,
     * @param bool $shouldHideCovered = false
     * @return HtmlHelper|\Mockery\MockInterface
     */
    protected function createHtmlHelper(
        Dirs $dirs = null,
        bool $inForcedProductionMode = false,
        bool $inDevMode = false,
        bool $shouldHideCovered = false
    ): HtmlHelper
    {
        return new HtmlHelper($dirs ?? $this->getDirs(), $this->getEnvironment(), $inDevMode, $inForcedProductionMode, $shouldHideCovered);
    }

    protected function getEnvironment(): Environment
    {
        return new Environment();
    }

    /**
     * @return string|CalculatorConfiguration
     */
    protected function getConfigurationClass(): string
    {
        return CalculatorConfiguration::class;
    }

    protected function getRulesApplicationClass(): string
    {
        return CalculatorApplication::class;
    }

    /**
     * @param Configuration|null $configuration
     * @param HtmlHelper|null $htmlHelper
     * @return ServicesContainer|CalculatorServicesContainer
     */
    protected function createServicesContainer(Configuration $configuration = null, HtmlHelper $htmlHelper = null): ServicesContainer
    {
        return new CalculatorServicesContainer(
            $configuration ?? $this->getConfiguration(),
            $htmlHelper ?? $this->createHtmlHelper($this->getDirs())
        );
    }
}