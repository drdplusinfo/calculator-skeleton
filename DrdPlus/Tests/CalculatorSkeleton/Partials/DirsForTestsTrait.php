<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton\Partials;

trait DirsForTestsTrait
{
    use \DrdPlus\Tests\FrontendSkeleton\Partials\DirsForTestsTrait;

    protected function getGenericPartsRoot(): string
    {
        return __DIR__ . '/../../../../parts/calculator-skeleton';
    }
}