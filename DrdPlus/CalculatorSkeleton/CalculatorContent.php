<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Cache;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Web\Content;
use DrdPlus\RulesSkeleton\Web\Menu;
use DrdPlus\RulesSkeleton\WebVersions;
use DrdPlus\RulesSkeletonWeb\RulesWebContent;

class CalculatorContent extends Content
{
    public function __construct(
        RulesWebContent $rulesWebContent,
        HtmlHelper $htmlHelper,
        WebVersions $webVersions,
        Menu $menu,
        Cache $cache
    )
    {
        parent::__construct($rulesWebContent, $htmlHelper, $webVersions, $menu, $cache, self::FULL, null);
    }
}