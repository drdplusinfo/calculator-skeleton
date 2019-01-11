<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\CalculatorSkeletonWeb\CalculatorWebContent;
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
    /** @var Memory */
    private $memory;
    /** @var CurrentValues */
    private $currentValues;
    /** @var History */
    private $history;
    /** @var CalculatorContent */
    private $calculatorContent;
    /** @var CalculatorWebContent */
    private $calculatorWebContent;

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

    public function getMemory(): Memory
    {
        if ($this->memory === null) {
            $this->memory = new Memory(
                $this->getCookiesService(),
                $this->getCalculatorRequest()->isRequestedHistoryDeletion(),
                $this->getRequest()->getValuesFromGet(),
                $this->getCalculatorRequest()->isRequestedRememberCurrent(),
                $this->getConfiguration()->getCookiesPostfix(),
                $this->getConfiguration()->getCookiesTtl()
            );
        }

        return $this->memory;
    }

    public function getCurrentValues(): CurrentValues
    {
        if ($this->currentValues === null) {
            $this->currentValues = new CurrentValues($this->getRequest()->getValuesFromGet(), $this->getMemory());
        }

        return $this->currentValues;
    }

    public function getHistory(): History
    {
        if ($this->history === null) {
            $this->history = new History(
                $this->getCookiesService(),
                $this->getCalculatorRequest()->isRequestedHistoryDeletion(),
                $this->getRequest()->getValuesFromGet(),
                $this->getCalculatorRequest()->isRequestedRememberCurrent(),
                $this->getConfiguration()->getCookiesPostfix(),
                $this->getConfiguration()->getCookiesTtl()
            );
        }

        return $this->history;
    }

    public function getCalculatorContent(): CalculatorContent
    {
        if ($this->calculatorContent === null) {
            $this->calculatorContent = new CalculatorContent(
                $this->getCalculatorWebContent(),
                $this->getHtmlHelper(),
                $this->getWebVersions(),
                $this->getMenu(),
                $this->getWebCache()
            );

        }

        return $this->calculatorContent;

    }

    public function getCalculatorWebContent(): CalculatorWebContent
    {
        if ($this->calculatorWebContent === null) {
            $this->calculatorWebContent = new CalculatorWebContent(
                $this->getHtmlHelper(),
                $this->getHead(),
                $this->getCalculatorBody()
            );
        }

        return $this->calculatorWebContent;
    }
}