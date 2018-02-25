<?php
namespace DrdPlus\Tests\Configurator\Skeleton;

use DrdPlus\Configurator\Skeleton\History;
use PHPUnit\Framework\TestCase;

class HistoryTest extends TestCase
{
    /**
     * @test
     * @runInSeparateProcess
     */
    public function Values_from_url_get_have_priority(): void
    {
        $history = new History(
            true, // remove previous history, if any
            ['from' => 'inner memory'],
            true, // remember current values
            'foo'
        );
        self::assertTrue($history->shouldRememberCurrent());
        self::assertSame('inner memory', $history->getValue('from'));
        $_GET['from'] = 'get';
        self::assertSame('get', $history->getValue('from'));
        unset($_GET['from']);
        self::assertSame('inner memory', $history->getValue('from'));
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function History_is_immediately_forgotten_if_requested(): void
    {
        $fooHistory = new History(
            true, // remove previous history, if any
            ['foo' => 'FOO'],
            true, // remember current values
            __FUNCTION__ // cookies prefix
        );
        self::assertSame('FOO', $fooHistory->getValue('foo'));
        $barHistory = new History(
            false, // do NOT remove previous history
            ['bar' => 'BAR'],
            true, // remember current values
            __FUNCTION__ // cookies prefix
        );
        self::assertSame('FOO', $fooHistory->getValue('foo'), 'Existing instances should NOT be affected');
        self::assertNull($barHistory->getValue('foo'));
        self::assertSame('BAR', $barHistory->getValue('bar'));
        $anotherHistory = new History(
            false, // do NOT remove previous history
            ['baz' => 'BAZ'],
            true, // remember current values
            __FUNCTION__ // cookies prefix
        );
        self::assertSame('FOO', $fooHistory->getValue('foo'));
        self::assertNull($anotherHistory->getValue('foo'));
        self::assertSame('BAR', $barHistory->getValue('bar'), 'Existing instances should NOT be affected');
        self::assertNull($anotherHistory->getValue('bar'));
        self::assertSame('BAZ', $anotherHistory->getValue('baz'));
        $yetAnotherHistory = new History(
            false, // do NOT remove previous history
            ['baz' => 'BAZ'],
            true, // remember current values
            __FUNCTION__ // cookies prefix
        );
        self::assertNull($yetAnotherHistory->getValue('foo'));
        self::assertNull($yetAnotherHistory->getValue('bar'));
        self::assertSame('BAZ', $yetAnotherHistory->getValue('baz'));
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function History_is_truncated_when_current_values_are_empty_only_if_cookie_history_expires(): void
    {
        $fooHistory = new History(
            true, // remove previous history, if any
            ['foo' => 'FOO'],
            true, // remember current values
            __FUNCTION__ // cookies prefix
        );
        self::assertSame('FOO', $fooHistory->getValue('foo'));
        $barHistory = new History(
            false, // do NOT remove previous history
            ['bar' => 'BAR'],
            true, // remember current values
            __FUNCTION__, // cookies prefix
            -1 // TTL
        );
        self::assertSame('FOO', $fooHistory->getValue('foo'), 'Existing instances should NOT be affected');
        self::assertNull($barHistory->getValue('foo'));
        self::assertSame('BAR', $barHistory->getValue('bar'));
        $anotherHistory = new History(
            false, // do NOT remove previous history
            [], // empty values
            true, // remember current values
            __FUNCTION__ // cookies prefix
        );
        self::assertNull($anotherHistory->getValue('foo'));
        self::assertSame('BAR', $anotherHistory->getValue('bar'), 'Nothing should changed with empty current values');
        $_COOKIE['configurator_history_token-' . __FUNCTION__] = false;
        $yetAnotherHistory = new History(
            false, // do NOT remove previous history
            [], // empty values
            false, // do NOT remember current values
            __FUNCTION__ // cookies prefix
        );
        self::assertNull($anotherHistory->getValue('foo'));
        self::assertNull($yetAnotherHistory->getValue('bar'));
    }
}
