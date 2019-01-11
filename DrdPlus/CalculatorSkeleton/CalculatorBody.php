<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Web\WebFiles;
use Granam\WebContentBuilder\Web\Body;

class CalculatorBody extends Body
{
    /**
     * @var CalculatorRequest
     */
    private $calculatorRequest;

    public function __construct(WebFiles $webFiles, CalculatorRequest $calculatorRequest)
    {
        parent::__construct($webFiles);
        $this->calculatorRequest = $calculatorRequest;
    }

    protected function fetchPhpFileContent(string $file): string
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $calculatorRequest = $this->calculatorRequest;
        \ob_start();
        /** @noinspection PhpIncludeInspection */
        include $file;

        return \ob_get_clean();
    }

}