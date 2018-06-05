<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\FrontendSkeleton\Controller;
use Granam\Tests\ExceptionsHierarchy\Exceptions\AbstractExceptionsHierarchyTest;

class CalculatorSkeletonExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     * @throws \ReflectionException
     */
    protected function getTestedNamespace(): string
    {
        return (new \ReflectionClass(Controller::class))->getNamespaceName();
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