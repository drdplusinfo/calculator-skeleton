<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Web\DebugContactsBody;
use DrdPlus\RulesSkeleton\Web\RulesMainBody;
use DrdPlus\RulesSkeleton\Web\WebFiles;

class CalculatorBody extends RulesMainBody
{
    /** @var CalculatorRequest */
    private $calculatorRequest;
    /** @var string */
    private $debugContacts;

    public function __construct(WebFiles $webFiles, DebugContactsBody $debugContactsBody, CalculatorRequest $calculatorRequest)
    {
        parent::__construct($webFiles, $debugContactsBody);
        $this->debugContacts = $debugContactsBody->getValue();
        $this->calculatorRequest = $calculatorRequest;
    }

    protected function fetchPhpFileContent(string $file): string
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $calculatorRequest = $this->calculatorRequest;
        /** @noinspection PhpUnusedLocalVariableInspection */
        $debugContacts = $this->debugContacts;
        \ob_start();
        /** @noinspection PhpIncludeInspection */
        include $file;

        return \ob_get_clean();
    }

}