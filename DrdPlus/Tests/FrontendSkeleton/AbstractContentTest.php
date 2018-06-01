<?php
namespace DrdPlus\Tests\FrontendSkeleton;

use DrdPlus\FrontendSkeleton\HtmlHelper;
use Gt\Dom\HTMLDocument;

abstract class AbstractContentTest extends SkeletonTestCase
{
    private static $contents = [];
    private static $htmlDocuments = [];

    protected function setUp()
    {
        if (!\defined('DRD_PLUS_INDEX_FILE_NAME_TO_TEST')) {
            self::markTestSkipped('Missing constant \'DRD_PLUS_INDEX_FILE_NAME_TO_TEST\'');
        }
    }

    /**
     * @param string $show = ''
     * @param array $get = []
     * @return string
     */
    protected function getContent(string $show = '', array $get = []): string
    {
        $key = $this->createKey($show, $get);
        if ((self::$contents[$key] ?? null) === null) {
            if ($show !== '') {
                $_GET['show'] = $show;
            }
            if ($get) {
                $_GET = \array_merge($_GET, $get);
            }
            $this->passIn();
            \ob_start();
            /** @noinspection PhpIncludeInspection */
            include DRD_PLUS_INDEX_FILE_NAME_TO_TEST;
            self::$contents[$key] = \ob_get_clean();
            self::assertNotEmpty(self::$contents[$key]);
            unset($_GET['show']);
        }

        return self::$contents[$key];
    }

    protected function createKey(string $show, array $get): string
    {
        return "{$this->passIn()}-$show-" . \serialize($get);
    }

    /**
     * Intended for overwrite if protected content is accessed
     */
    protected function passIn(): bool
    {
        return true;
    }

    protected function getHtmlDocument(string $show = '', array $get = []): \DrdPlus\FrontendSkeleton\HtmlDocument
    {
        $key = $this->createKey($show, $get);
        if (empty(self::$htmlDocuments[$key])) {
            self::$htmlDocuments[$key] = new \DrdPlus\FrontendSkeleton\HtmlDocument($this->getContent($show, $get));
        }

        return self::$htmlDocuments[$key];
    }

    protected function isSkeletonChecked(HTMLDocument $document): bool
    {
        return \strpos($this->getCurrentPageTitle($document), 'skeleton') !== false;
    }

    protected function getCurrentPageTitle(HTMLDocument $document = null): string
    {
        $head = ($document ?? $this->getHtmlDocument())->head;
        if (!$head) {
            return '';
        }
        $titles = $head->getElementsByTagName('title');
        if ($titles->count() === 0) {
            return '';
        }
        $titles->rewind();

        return $titles->current()->nodeValue;
    }

    protected function getDocumentRoot(): string
    {
        return \dirname(DRD_PLUS_INDEX_FILE_NAME_TO_TEST);
    }

    protected function getDefinedPageTitle(): string
    {
        return (new HtmlHelper($this->getDocumentRoot(), false, false, false, false))->getPageTitle();
    }

}