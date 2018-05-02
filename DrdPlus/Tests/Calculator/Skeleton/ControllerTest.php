<?php
namespace DrdPlus\Tests\Calculator\Skeleton;

use DrdPlus\Calculator\Skeleton\Controller;
use DrdPlus\Calculator\Skeleton\History;
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    /**
     * @test
     * @runInSeparateProcess
     * @backupGlobals
     * @throws \ReflectionException
     */
    public function Current_memory_is_affected_by_current_get(): void
    {
        $reflection = new \ReflectionClass(Controller::class);
        $constructor = $reflection->getMethod('__construct');
        $constructor->setAccessible(true);
        $controller = \Mockery::mock(Controller::class);
        $_GET['bar'] = 'baz';
        $_GET[Controller::REMEMBER_CURRENT] = true;
        $constructor->invoke($controller, 'foo', 123 /* cookies TTL */);
        $getMemory = $reflection->getMethod('getMemory');
        $getMemory->setAccessible(true);
        /** @var History $history */
        $history = $getMemory->invoke($controller);
        self::assertTrue($history->shouldRememberCurrent());
        self::assertSame('baz', $history->getValue('bar'));
    }

    /**
     * @test
     * @runInSeparateProcess
     * @backupGlobals
     * @throws \ReflectionException
     */
    public function Current_history_is_not_affected_by_current_get(): void
    {
        $reflection = new \ReflectionClass(Controller::class);
        $constructor = $reflection->getMethod('__construct');
        $constructor->setAccessible(true);
        $controller = \Mockery::mock(Controller::class);
        $_GET['bar'] = 'baz';
        $_GET[Controller::REMEMBER_CURRENT] = true;
        $constructor->invoke($controller, 'foo', 123 /* cookies TTL */);
        $getHistory = $reflection->getMethod('getHistory');
        $getHistory->setAccessible(true);
        /** @var History $history */
        $history = $getHistory->invoke($controller);
        self::assertTrue($history->shouldRememberCurrent());
        self::assertNull($history->getValue('bar'));
        unset($_GET['bar']);
        $constructor->invoke($controller, 'foo', 123 /* cookies TTL */); // creates history again
        $nextHistory = $getHistory->invoke($controller);
        self::assertSame('baz', $nextHistory->getValue('bar'));
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
        $constructor->invoke($controller, 'foo', 123 /* cookies TTL */);
        $controller->shouldDeferMissing();
        self::assertSame($_SERVER['REQUEST_URI'], $controller->getRequestUrl());
        self::assertSame(
            \str_replace(['remember_current=1', '[]'], ['remember_current=0', \urlencode('[]')], $_SERVER['REQUEST_URI']),
            $controller->getRequestUrl(['remember_current' => '0'])
        );
    }
}