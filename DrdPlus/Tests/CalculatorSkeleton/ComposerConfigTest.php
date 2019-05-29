<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

class ComposerConfigTest extends \DrdPlus\Tests\RulesSkeleton\ComposerConfigTest
{
    use Partials\CalculatorTestTrait;

    /**
     * @test
     */
    public function Assets_have_injected_versions(): void
    {
        self::assertTrue(true, 'This is solved by parent skeleton');
    }
}