<?php
namespace DrdPlus\Tests\Configurator;

use DrdPlus\Configurator\Skeleton\Controller;
use DrdPlus\Configurator\Skeleton\History;
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    /**
     * @test
     * @runInSeparateProcess
     * @backupGlobals
     */
    public function I_can_get_history_with_expected_cookies_sufix_and_ttl(): void
    {
        $reflection = new \ReflectionClass(Controller::class);
        $constructor = $reflection->getMethod('__construct');
        $constructor->setAccessible(true);
        $controller = \Mockery::mock(Controller::class);
        $_GET['bar'] = 'baz';
        $_GET[Controller::REMEMBER_HISTORY] = true;
        $constructor->invoke($controller, 'foo', 123 /* cookies TTL */);
        $getHistory = $reflection->getMethod('getHistory');
        $getHistory->setAccessible(true);
        /** @var History $history */
        $history = $getHistory->invoke($controller);

        self::assertTrue($history->shouldRemember());
        self::assertSame('baz', $history->getValue('bar'));
    }
}