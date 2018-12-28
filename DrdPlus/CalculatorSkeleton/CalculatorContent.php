<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Cache;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Web\Content;
use DrdPlus\RulesSkeleton\Web\Head;
use DrdPlus\RulesSkeleton\Web\Menu;
use DrdPlus\RulesSkeleton\WebVersions;

class CalculatorContent extends Content
{
    public function __construct(
        HtmlHelper $htmlHelper,
        WebVersions $webVersions,
        Head $head,
        Menu $menu,
        CalculatorBody $body,
        Cache $cache
    )
    {
        parent::__construct($htmlHelper, $webVersions, $head, $menu, $body, $cache, self::FULL, null);
    }
}