<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\CalculatorApplication;
use DrdPlus\Tests\RulesSkeleton\RulesSkeletonExceptionsHierarchyTest;
use Granam\Tests\ExceptionsHierarchy\Exceptions\AbstractExceptionsHierarchyTest;

class CalculatorSkeletonExceptionsHierarchyTest extends RulesSkeletonExceptionsHierarchyTest
{
    use Partials\CalculatorTestTrait;

    /**
     * @return string
     * @throws \ReflectionException
     */
    protected function getTestedNamespace(): string
    {
        return (new \ReflectionClass(CalculatorApplication::class))->getNamespaceName();
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    protected function getRootNamespace(): string
    {
        return $this->getTestedNamespace();
    }

}