<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Cache;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Web\Content;
use DrdPlus\RulesSkeleton\Web\Menu;
use DrdPlus\RulesSkeleton\WebVersions;

class CalculatorContent extends Content
{
    public function __construct(
        CalculatorWebContent $calculatorWebContent,
        HtmlHelper $htmlHelper,
        WebVersions $webVersions,
        Menu $menu,
        Cache $cache
    )
    {
        parent::__construct($calculatorWebContent, $htmlHelper, $webVersions, $menu, $cache, self::FULL, null);
    }
}