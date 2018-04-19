<?php
namespace DrdPlus\Tests\Configurator\Skeleton;

use DrdPlus\Configurator\Skeleton\Controller;
use DrdPlus\Configurator\Skeleton\History;
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
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
}