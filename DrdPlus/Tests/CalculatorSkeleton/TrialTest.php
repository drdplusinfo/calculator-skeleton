<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\CalculatorController;
use DrdPlus\CalculatorSkeleton\Dirs;
use DrdPlus\FrontendSkeleton\CookiesService;
use DrdPlus\FrontendSkeleton\FrontendController;
use DrdPlus\FrontendSkeleton\HtmlHelper;

class TrialTest extends \DrdPlus\Tests\FrontendSkeleton\TrialTest
{
    use Partials\AbstractContentTestTrait;

    protected function createController(): FrontendController
    {
        $dirs = new Dirs($this->getMasterDocumentRoot(), $this->getDocumentRoot());

        return new CalculatorController(
            'Google analytics ID foo',
            new HtmlHelper($dirs, true, false, false, false),
            $dirs,
            'https://example.com',
            new CookiesService(),
            'foo'
        );
    }
}