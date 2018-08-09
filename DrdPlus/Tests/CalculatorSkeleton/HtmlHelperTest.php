<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\FrontendSkeleton\HtmlHelper;

class HtmlHelperTest extends \DrdPlus\Tests\FrontendSkeleton\HtmlHelperTest
{
    use Partials\AbstractContentTestTrait;

    protected static function getSutClass(string $sutTestClass = null, string $regexp = '~\\\Tests(.+)Test$~'): string
    {
        return HtmlHelper::class;
    }

}