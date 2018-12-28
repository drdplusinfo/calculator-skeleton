<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\ServicesContainer;

/**
 * @method CalculatorConfiguration getConfiguration()
 */
class CalculatorServicesContainer extends ServicesContainer
{
    /** @var CalculatorBody */
    private $calculatorBody;
    /** @var CalculatorRequest */
    private $calculatorRequest;

    public function __construct(CalculatorConfiguration $calculatorConfiguration, HtmlHelper $htmlHelper)
    {
        parent::__construct($calculatorConfiguration, $htmlHelper);
    }

    public function getCalculatorBody(): CalculatorBody
    {
        if ($this->calculatorBody === null) {
            $this->calculatorBody = new CalculatorBody($this->getWebFiles(), $this->getCalculatorRequest());
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