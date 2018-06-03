<?php
namespace DrdPlus\Tests\Skeleton;

use DrdPlus\CalculatorSkeleton\Controller;
use DrdPlus\CalculatorSkeleton\History;
use DrdPlus\CalculatorSkeleton\Memory;
use Granam\Tests\Tools\TestWithMockery;

class ControllerTest extends TestWithMockery
{
    /**
     * @test
     * @runInSeparateProcess
     * @backupGlobals enabled
     * @throws \ReflectionException
     */
    public function Current_memory_is_affected_by_current_get(): void
    {
        $reflection = new \ReflectionClass(Controller::class);
        $constructor = $reflection->getMethod('__construct');
        $constructor->setAccessible(true);
        $controller = \Mockery::mock(Controller::class);
        $controller->makePartial(); // call original methods if not mocked
        $_GET['qux'] = 'baz';
        $_GET[Controller::REMEMBER_CURRENT] = true;
        $constructor->invoke($controller, 'foo', 'https://example.com', 'bar', 123 /* cookies TTL */);
        self::assertSame('foo', $controller->getDocumentRoot());
        self::assertSame('https://example.com', $controller->getSourceCodeUrl());
        $getMemory = $reflection->getMethod('getMemory');
        $getMemory->setAccessible(true);
        /** @var Memory $memory */
        $memory = $getMemory->invoke($controller);
        self::assertFalse($memory->shouldForgotMemory());
        self::assertSame('baz', $memory->getValue('qux'));
    }

    /**
     * @test
     * @runInSeparateProcess
     * @backupGlobals enabled
     * @throws \ReflectionException
     */
    public function Current_history_is_not_affected_by_current_get(): void
    {
        $reflection = new \ReflectionClass(Controller::class);
        $constructor = $reflection->getMethod('__construct');
        $constructor->setAccessible(true);
        $controller = \Mockery::mock(Controller::class);
        $_GET['qux'] = 'baz';
        $_GET[Controller::REMEMBER_CURRENT] = true;
        $constructor->invoke($controller, 'foo', 'https://example.com', 'bar', 123 /* cookies TTL */);
        $getHistory = $reflection->getMethod('getHistory');
        $getHistory->setAccessible(true);
        /** @var History $history */
        $history = $getHistory->invoke($controller);
        self::assertFalse($history->shouldForgotHistory());
        self::assertNull($history->getValue('qux'));
        unset($_GET['qux']);
        $constructor->invoke($controller, 'foo', 'https://example.com', 'bar', 123 /* cookies TTL */); // creates history again
        $nextHistory = $getHistory->invoke($controller);
        self::assertSame('baz', $nextHistory->getValue('qux'));
    }

    /**
     * @test
     * @runInSeparateProcess
     * @throws \ReflectionException
     */
    public function I_can_get_original_as_well_as_modified_url(): void
    {
        $reflection = new \ReflectionClass(Controller::class);
        $constructor = $reflection->getMethod('__construct');
        $constructor->setAccessible(true);
        /** @var Controller|\Mockery\MockInterface $controller */
        $controller = \Mockery::mock(Controller::class);
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
        $constructor->invoke($controller, 'foo', 'https://example.com', 'bar', 123 /* cookies TTL */);
        $controller->makePartial();
        self::assertSame($_SERVER['REQUEST_URI'], $controller->getRequestUrl());
        self::assertSame(
            \str_replace(['remember_current=1', '[]'], ['remember_current=0', \urlencode('[]')], $_SERVER['REQUEST_URI']),
            $controller->getRequestUrl(['remember_current' => '0'])
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\CalculatorSkeleton\Exceptions\SourceCodeUrlIsNotValid
     * @throws \ReflectionException
     */
    public function I_can_not_create_it_with_invalid_source_code_url(): void
    {
        $reflection = new \ReflectionClass(Controller::class);
        $constructor = $reflection->getMethod('__construct');
        $constructor->setAccessible(true);
        $controller = \Mockery::mock(Controller::class);
        $constructor->invoke($controller, 'foo', 'codeOnMyDisk', 'bar', 123 /* cookies TTL */);
    }
}