<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

class Dirs extends \DrdPlus\FrontendSkeleton\Dirs
{
    protected function populateSubRoots(string $masterDocumentRoot, string $documentRoot): void
    {
        parent::populateSubRoots($masterDocumentRoot, $documentRoot);
        $this->genericPartsRoot = __DIR__ . '/../../parts/calculator-skeleton';
    }
}