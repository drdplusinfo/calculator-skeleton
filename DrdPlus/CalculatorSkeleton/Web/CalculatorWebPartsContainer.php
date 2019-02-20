<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton\Web;

use DrdPlus\CalculatorSkeleton\CalculatorServicesContainer;
use DrdPlus\RulesSkeleton\Web\WebPartsContainer;

class CalculatorWebPartsContainer extends WebPartsContainer
{

    /** @var HistoryDeletionBody */
    private $historyDeletionBody;

    /** @var CalculatorDebugContactsBody */
    private $calculatorDebugContactsBody;

    public function __construct(CalculatorServicesContainer $calculatorServicesContainer)
    {
        parent::__construct($calculatorServicesContainer);
    }

    public function getHistoryDeletionBody(): HistoryDeletionBody
    {
        if ($this->historyDeletionBody === null) {
            $this->historyDeletionBody = new HistoryDeletionBody();
        }
        return $this->historyDeletionBody;
    }

    public function getCalculatorDebugContactsBody(): CalculatorDebugContactsBody
    {
        if ($this->calculatorDebugContactsBody === null) {
            $this->calculatorDebugContactsBody = new CalculatorDebugContactsBody();
        }
        return $this->calculatorDebugContactsBody;
    }

}