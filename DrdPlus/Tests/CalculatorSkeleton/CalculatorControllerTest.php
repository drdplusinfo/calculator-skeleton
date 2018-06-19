<?php
namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\CalculatorController;
use DrdPlus\CalculatorSkeleton\History;
use DrdPlus\CalculatorSkeleton\Memory;
use DrdPlus\Tests\FrontendSkeleton\AbstractContentTest;

class CalculatorControllerTest extends AbstractContentTest
{
    /**
     * @test
     * @throws \ReflectionException
     */
    public function Current_memory_is_affected_by_current_get(): void
    {
        $_GET['qux'] = 'quux';
        $_GET[CalculatorController::REMEMBER_CURRENT] = true;
        $controller = new CalculatorController(
            $this->createHtmlHelper(),
            'https://example.com',
            'baz',
            'foo',
            'vendor root',
            123 /* cookies TTL */
        );
        self::assertSame('foo', $controller->getDocumentRoot());
        self::assertSame('vendor root', $controller->getVendorRoot());
        self::assertSame('https://example.com', $controller->getSourceCodeUrl());
        $reflection = new \ReflectionClass(CalculatorController::class);
        $getMemory = $reflection->getMethod('getMemory');
        $getMemory->setAccessible(true);
        /** @var Memory $memory */
        $memory = $getMemory->invoke($controller);
        self::assertFalse($memory->shouldForgotMemory());
        self::assertSame('quux', $memory->getValue('qux'));
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function Current_history_is_not_affected_by_current_get(): void
    {
        $_GET['qux'] = 'baz';
        $_GET[CalculatorController::REMEMBER_CURRENT] = true;
        $controller = new CalculatorController(
            $this->createHtmlHelper(),
            'https://example.com',
            'bar',
            'foo',
            'vendor root',
            123 /* cookies TTL */
        );
        $reflection = new \ReflectionClass(CalculatorController::class);
        $getHistory = $reflection->getMethod('getHistory');
        $getHistory->setAccessible(true);
        /** @var History $history */
        $history = $getHistory->invoke($controller);
        self::assertFalse($history->shouldForgotHistory());
        self::assertNull($history->getValue('qux'));
        unset($_GET['qux']);
        $controller = new CalculatorController( // creates history again
            $this->createHtmlHelper(),
            'https://example.com',
            'bar',
            'foo',
            'vendor root',
            123 /* cookies TTL */
        );
        $nextHistory = $getHistory->invoke($controller);
        self::assertSame('baz', $nextHistory->getValue('qux'));
    }

    /**
     * @test
     */
    public function I_can_get_original_as_well_as_modified_url(): void
    {
        $_SERVER['REQUEST_URI'] = 'http://odpocinek.drdplus.loc/?remember_current=1&strength=0&will=0&race=human&sub_race=common&gender=male&roll_against_malus_from_wounds=9&fresh_wound_size[]=1&serious_wound_origin[]=mechanical_stab';
        $_GET = [
            'remember_current' => '1',
            'strength' => '0',
            'will' => '0',
            'race' => 'human',
            'sub_race' => 'common',
            'gender' => 'male',
            'roll_against_malus_from_wounds' => '9',
            'fresh_wound_size' => ['1'],
            'serious_wound_origin' => ['mechanical_stab'],
        ];
        $controller = new CalculatorController(
            $this->createHtmlHelper(),
            'https://example.com',
            'bar',
            'foo',
            'vendor root',
            123 /* cookies TTL */
        );
        self::assertSame($_SERVER['REQUEST_URI'], $controller->getRequestUrl());
        self::assertSame(
            \str_replace(['remember_current=1', '[]'], ['remember_current=0', \urlencode('[]')], $_SERVER['REQUEST_URI']),
            $controller->getRequestUrl(['remember_current' => '0'])
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\CalculatorSkeleton\Exceptions\SourceCodeUrlIsNotValid
     */
    public function I_can_not_create_it_with_invalid_source_code_url(): void
    {
        new CalculatorController(
            $this->createHtmlHelper(),
            'codeOnMyDisk',
            'foo',
            'vendor root',
            'bar',
            123 /* cookies TTL */
        );
    }
}