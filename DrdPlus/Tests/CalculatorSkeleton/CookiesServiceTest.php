<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\FrontendSkeleton\CookiesService;

class CookiesServiceTest extends \DrdPlus\Tests\FrontendSkeleton\CookiesServiceTest
{
    use Partials\AbstractContentTestTrait;

    protected static function getSutClass(string $sutTestClass = null, string $regexp = '~\\\Tests(.+)Test$~'): string
    {
        return CookiesService::class;
    }
}