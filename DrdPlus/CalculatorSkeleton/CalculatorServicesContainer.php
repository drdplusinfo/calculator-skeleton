<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\ServicesContainer;
use DrdPlus\RulesSkeleton\Web\RulesMainContent;

/**
 * @method CalculatorConfiguration getConfiguration()
 */
class CalculatorServicesContainer extends ServicesContainer
{
    /** @var CalculatorContent */
    private $calculatorMainContent;
    /** @var CalculatorMainBody */
    private $calculatorBody;
    /** @var CalculatorRequest */
    private $calculatorRequest;

    public function __construct(CalculatorConfiguration $calculatorConfiguration, HtmlHelper $htmlHelper)
    {
        parent::__construct($calculatorConfiguration, $htmlHelper);
    }

    public function getCalculatorMainContent(): CalculatorMainContent
    {
        if ($this->calculatorMainContent === null) {
            $this->calculatorMainContent = new CalculatorMainContent(
                $this->getHtmlHelper(),
                $this->getHead(),
                $this->getCalculatorBody()
            );
        }
        return $this->calculatorMainContent;
    }

    public function getCalculatorBody(): CalculatorMainBody
    {
        if ($this->calculatorBody === null) {
            $this->calculatorBody = new CalculatorMainBody($this->getWebFiles(), $this->getCalculatorRequest());
        }

        return $this->calculatorBody;
    }

    public function getCalculatorRequest(): CalculatorRequest
    {
        if ($this->calculatorRequest === null) {
            $this->calculatorRequest = new CalculatorRequest($this->getBotParser());
        }

        return $this->calculatorRequest;
    }

}