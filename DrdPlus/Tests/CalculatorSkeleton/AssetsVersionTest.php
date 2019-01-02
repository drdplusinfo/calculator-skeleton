<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\AssetsVersion;

class AssetsVersionTest extends \DrdPlus\Tests\RulesSkeleton\AssetsVersionTest
{
    use Partials\AbstractContentTestTrait;

    protected static function getSutClass(string $sutTestClass = null, string $regexp = '~\\\Tests(.+)Test$~'): string
    {
        return AssetsVersion::class;
    }
}