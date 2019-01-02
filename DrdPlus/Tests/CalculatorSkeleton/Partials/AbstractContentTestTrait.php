<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton\Partials;

use DrdPlus\RulesSkeleton\Dirs;
use DrdPlus\RulesSkeleton\HtmlHelper;

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
        self::assertSame(
            'kalkulator.skeleton',
            \basename($skeletonRootRealPath),
            'Expected different trailing dir of calculator skeleton document root to detect it'
        );

        return $documentRootRealPath === $skeletonRootRealPath;
    }

    /**
     * @param Dirs $dirs
     * @param bool $inDevMode
     * @param bool $inForcedProductionMode
     * @param bool $shouldHideCovered
     * @return HtmlHelper|\Mockery\MockInterface
     */
    protected function createHtmlHelper(
        Dirs $dirs = null,
        bool $inForcedProductionMode = false,
        bool $inDevMode = false,
        bool $shouldHideCovered = false
    ): HtmlHelper
    {
        return new HtmlHelper($dirs ?? $this->createDirs(), $inDevMode, $inForcedProductionMode, $shouldHideCovered);
    }
}