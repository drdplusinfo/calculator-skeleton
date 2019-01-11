<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeletonWeb\RulesWebContent;
use Granam\WebContentBuilder\HtmlHelper;
use Granam\WebContentBuilder\Web\Head;

class CalculatorWebContent extends RulesWebContent
{
    public function __construct(HtmlHelper $htmlHelper, Head $head, CalculatorBody $calculatorBody)
    {
        parent::__construct($htmlHelper, $head, $calculatorBody);
    }

}