<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton\Partials;

use DrdPlus\FrontendSkeleton\Dirs;
use DrdPlus\FrontendSkeleton\HtmlHelper;

/**
 * @method Dirs createDirs
 * @method string getDocumentRoot
 * @method TestsConfigurationReader getTestsConfiguration
 * @method static assertTrue($value, $message = '')
 * @method static assertFalse($value, $message = '')
 * @method static assertSame($expected, $actual, $message = '')
 * @method static assertNotSame($expected, $actual, $message = '')
 * @method static assertNotEmpty($value, $message = '')
 * @method static fail($message)
 */
trait AbstractContentTestTrait
{

    protected function isSkeletonChecked(string $skeletonDocumentRoot = null): bool
    {
        $documentRootRealPath = \realpath($this->getDocumentRoot());
        self::assertNotEmpty($documentRootRealPath, 'Can not find out real path of document root ' . \var_export($this->getDocumentRoot(), true));
        $skeletonRootRealPath = \realpath($skeletonDocumentRoot ?? __DIR__ . '/../../../..');
        self::assertNotEmpty($skeletonRootRealPath, 'Can not find out real path of skeleton root ' . \var_export($skeletonRootRealPath, true));
        self::assertSame('calculator-skeleton', \basename($skeletonRootRealPath), 'Expected different trailing dir of skeleton document root');

        return $documentRootRealPath === $skeletonRootRealPath;
    }

    protected function getGenericPartsRoot(): string
    {
        return \file_exists($this->getDocumentRoot() . '/parts/calculator-skeleton')
            ? $this->getDocumentRoot() . '/parts/calculator-skeleton'
            : $this->getVendorRoot() . '/drd-plus/calculator-skeleton/parts/calculator-skeleton';
    }

    protected function getVendorRoot(): string
    {
        return $this->getDocumentRoot() . '/vendor';
    }

    /**
     * @param Dirs $dirs
     * @param bool $inDevMode
     * @param bool $inForcedProductionMode
     * @param bool $shouldHideCovered
     * @param bool $showIntroductionOnly
     * @return HtmlHelper|\Mockery\MockInterface
     */
    protected function createHtmlHelper(
        Dirs $dirs = null,
        bool $inForcedProductionMode = false,
        bool $inDevMode = false,
        bool $shouldHideCovered = false,
        bool $showIntroductionOnly = false
    ): HtmlHelper
    {
        return new HtmlHelper($dirs ?? $this->createDirs(), $inDevMode, $inForcedProductionMode, $shouldHideCovered, $showIntroductionOnly);
    }
}