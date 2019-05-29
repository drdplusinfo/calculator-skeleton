<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\CalculatorRequest;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class CalculatorContentTest extends AbstractContentTest
{
    use Partials\CalculatorTestTrait;

    /**
     * @test
     */
    public function I_can_delete_history(): void
    {
        $htmlDocument = $this->getHtmlDocument();
        $inputs = $htmlDocument->getElementsByTagName('input');
        self::assertNotCount(0, $inputs, 'No inputs found therefore button for history deletion is missing');
        foreach ($inputs as $input) {
            if ($input->getAttribute('name') === CalculatorRequest::DELETE_HISTORY) {
                self::assertNotEmpty($input->value, 'Value of button to delete history should not be empty');

                return;
            }
        }
        self::fail('Button for history deletion not found');
    }
}