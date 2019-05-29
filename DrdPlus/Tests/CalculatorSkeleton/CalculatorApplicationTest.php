<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\CalculatorApplication;
use DrdPlus\CalculatorSkeleton\CalculatorConfiguration;
use DrdPlus\CalculatorSkeleton\CalculatorRequest;
use DrdPlus\CalculatorSkeleton\CalculatorServicesContainer;
use DrdPlus\CalculatorSkeleton\History;
use DrdPlus\CalculatorSkeleton\Memory;
use DrdPlus\RulesSkeleton\CookiesService;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class CalculatorApplicationTest extends AbstractContentTest
{
    use Partials\CalculatorTestTrait;

    /**
     * @test
     * @throws \ReflectionException
     */
    public function Current_memory_is_affected_by_current_get(): void
    {
        $_GET['qux'] = 'quux';
        $_GET[CalculatorRequest::REMEMBER_CURRENT] = true;
        $application = new CalculatorApplication($this->createCalculatorServicesContainer());
        $reflection = new \ReflectionClass(CalculatorApplication::class);
        $getMemory = $reflection->getMethod('getMemory');
        $getMemory->setAccessible(true);
        /** @var Memory $memory */
        $memory = $getMemory->invoke($application);
        self::assertFalse($memory->shouldForgotMemory());
        self::assertSame('quux', $memory->getValue('qux'));
    }

    protected function createCalculatorServicesContainer(array $settingsPart = []): CalculatorServicesContainer
    {
        return new CalculatorServicesContainer(
            $this->createCalculatorConfiguration($settingsPart),
            $this->createHtmlHelper()
        );
    }

    protected function createCalculatorConfiguration(array $settingsPart = [])
    {
        return new CalculatorConfiguration(
            $this->getDirs(),
            array_replace_recursive($this->getConfiguration()->getSettings(), $settingsPart)
        );
    }

    protected function createCookiesService(): CookiesService
    {
        return new CookiesService();
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function Current_history_is_not_affected_by_current_get(): void
    {
        $_GET['qux'] = 'baz';
        $_GET[CalculatorRequest::REMEMBER_CURRENT] = true;
        $application = new CalculatorApplication($this->createCalculatorServicesContainer());
        $reflection = new \ReflectionClass(CalculatorApplication::class);
        $getHistory = $reflection->getMethod('getHistory');
        $getHistory->setAccessible(true);
        /** @var History $history */
        $history = $getHistory->invoke($application);
        self::assertFalse($history->shouldForgotHistory());
        self::assertNull($history->getValue('qux'));
        unset($_GET['qux']);
        $nextApplication = new CalculatorApplication($this->createCalculatorServicesContainer());
        $nextHistory = $getHistory->invoke($nextApplication);
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
        $application = new CalculatorApplication($this->createCalculatorServicesContainer());
        self::assertSame($_SERVER['REQUEST_URI'], $application->getRequestUrl());
        self::assertSame(
            \str_replace(['remember_current=1', '[]'], ['remember_current=0', \urlencode('[]')], $_SERVER['REQUEST_URI']),
            $application->getRequestUrl(['remember_current' => '0'])
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\CalculatorSkeleton\Exceptions\InvalidCookiesPostfix
     */
    public function I_can_not_create_it_with_empty_cookies_postfix(): void
    {
        new CalculatorApplication($this->createCalculatorServicesContainer(
            [CalculatorConfiguration::WEB => [CalculatorConfiguration::COOKIES_POSTFIX => '']]
        ));
    }

    /**
     * @test
     * @expectedException \DrdPlus\CalculatorSkeleton\Exceptions\InvalidCookiesTtl
     * @expectedExceptionMessageRegExp 'forever'
     */
    public function I_can_not_create_it_with_invalid_cookies_ttl(): void
    {
        new CalculatorApplication($this->createCalculatorServicesContainer(
            [CalculatorConfiguration::WEB => [CalculatorConfiguration::COOKIES_TTL => 'forever']]
        ));
    }
}