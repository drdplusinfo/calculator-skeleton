<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Web\RulesBodyInterface;
use DrdPlus\RulesSkeleton\Web\WebFiles;
use Granam\WebContentBuilder\HtmlDocument;
use Granam\WebContentBuilder\Web\Body;

class CalculatorMainBody extends Body implements RulesBodyInterface
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
        ob_start();
        /** @noinspection PhpIncludeInspection */
        include $file;

        return ob_get_clean();
    }

    public function postProcessDocument(HtmlDocument $htmlDocument): HtmlDocument
    {
        return $htmlDocument; // no post-process
    }

}