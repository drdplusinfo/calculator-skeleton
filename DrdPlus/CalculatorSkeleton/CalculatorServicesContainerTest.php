<?php
namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\Tests\CalculatorSkeleton\ServicesContainerTest;

class CalculatorServicesContainerTest extends ServicesContainerTest
{
    /**
     * @test
     * @backupGlobals enabled
     */
    public function Current_memory_is_affected_by_current_get(): void
    {
        $_GET['qux'] = 'quux';
        $_GET[CalculatorRequest::REMEMBER_CURRENT] = true;
        $calculatorServicesContainer = new CalculatorServicesContainer($this->getConfiguration(), $this->createHtmlHelper());
        $memory = $calculatorServicesContainer->getMemory();
        self::assertFalse($memory->shouldForgotMemory());
        self::assertSame('quux', $memory->getValue('qux'));
    }

    /**
     * @test
     * @backupGlobals enabled
     */
    public function Current_history_is_not_affected_by_current_get(): void
    {
        $_GET['qux'] = 'baz';
        $_GET[CalculatorRequest::REMEMBER_CURRENT] = true;
        $calculatorServicesContainer = new CalculatorServicesContainer();
        $history = $calculatorServicesContainer->getHistory();
        self::assertFalse($history->shouldForgotHistory());
        self::assertNull($history->getValue('qux'));
        unset($_GET['qux']);
        $nextHistory = $calculatorServicesContainer->getHistory(); // creates history again
        self::assertSame('baz', $nextHistory->getValue('qux'));
    }

}
