<?php declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Web\MainContent;
use Granam\WebContentBuilder\Web\HeadInterface;

class CalculatorMainContent extends MainContent
{
    public function __construct(HtmlHelper $htmlHelper, HeadInterface $head, CalculatorMainBody $body)
    {
        parent::__construct($htmlHelper, $head, $body);
    }

}