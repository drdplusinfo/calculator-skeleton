<?php
namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\Controller;
use DrdPlus\Tests\FrontendSkeleton\AbstractContentTest;

class CalculatorContentTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function I_can_delete_history(): void
    {
        $htmlDocument = $this->getHtmlDocument();
        $inputs = $htmlDocument->getElementsByTagName('input');
        self::assertNotCount(0, $inputs, 'No inputs found so no button for history deletion');
        foreach ($inputs as $input) {
            if ($input->getAttribute('name') === Controller::DELETE_HISTORY) {
                self::assertNotEmpty($input->value, 'Value of button to delete history should not be empty');

                return;
            }
        }
        self::fail('Button for history deletion not found');
    }
}