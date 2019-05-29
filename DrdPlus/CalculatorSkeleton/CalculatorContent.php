<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Cache;
use DrdPlus\RulesSkeleton\CurrentWebVersion;
use DrdPlus\RulesSkeleton\Web\Menu;
use DrdPlus\RulesSkeleton\Web\RulesContent;

class CalculatorContent extends RulesContent
{
    public function __construct(
        CalculatorMainContent $calculatorMainContent,
        Menu $menu,
        CurrentWebVersion $currentWebVersion,
        Cache $cache
    )
    {
        parent::__construct($calculatorMainContent, $menu, $currentWebVersion, $cache, self::FULL, null);
    }
}