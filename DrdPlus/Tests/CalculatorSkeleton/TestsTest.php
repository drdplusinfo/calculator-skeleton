<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\Tests\RulesSkeleton\AbstractPublicFilesTest;
use DrdPlus\Tests\RulesSkeleton\CacheTest;
use DrdPlus\Tests\RulesSkeleton\ConfigurationTest;
use DrdPlus\Tests\RulesSkeleton\ContentIrrelevantParametersFilterTest;
use DrdPlus\Tests\RulesSkeleton\CookiesServiceTest;
use DrdPlus\Tests\RulesSkeleton\CurrentWebVersionTest;
use DrdPlus\Tests\RulesSkeleton\DirsTest;
use DrdPlus\Tests\RulesSkeleton\EnvironmentTest;
use DrdPlus\Tests\RulesSkeleton\HtmlHelperTest;
use DrdPlus\Tests\RulesSkeleton\PassingTest;
use DrdPlus\Tests\RulesSkeleton\RedirectTest;
use DrdPlus\Tests\RulesSkeleton\RequestTest;
use DrdPlus\Tests\RulesSkeleton\RouterTest;
use DrdPlus\Tests\RulesSkeleton\RulesApplicationTest;
use DrdPlus\Tests\RulesSkeleton\ServicesContainerTest;
use DrdPlus\Tests\RulesSkeleton\SkeletonInjectorComposerPluginTest;
use DrdPlus\Tests\RulesSkeleton\TableOfContentsTest;
use DrdPlus\Tests\RulesSkeleton\UsagePolicyTest;
use DrdPlus\Tests\RulesSkeleton\Web\HeadTest;
use DrdPlus\Tests\RulesSkeleton\Web\MainContentTest;
use DrdPlus\Tests\RulesSkeleton\Web\MenuTest;
use DrdPlus\Tests\RulesSkeleton\Web\PassContentTest;
use DrdPlus\Tests\RulesSkeleton\Web\RulesMainContentTest;

class TestsTest extends \DrdPlus\Tests\RulesSkeleton\TestsTest
{
    use Partials\CalculatorTestTrait;

    /**
     * @test
     * @throws \ReflectionException
     */
    public function All_rules_skeleton_tests_are_used(): void
    {
        $reflectionClass = new \ReflectionClass(RulesApplicationTest::class);
        $frontendSkeletonDir = \dirname($reflectionClass->getFileName());
        foreach ($this->getClassesFromDir($frontendSkeletonDir) as $parentSkeletonTestClass) {
            if (\is_a($parentSkeletonTestClass, \Throwable::class, true)
                || \is_a($parentSkeletonTestClass, RulesApplicationTest::class, true) // it is solved via CalculatorApplication
            ) {
                continue;
            }
            $frontendSkeletonTestClassReflection = new \ReflectionClass($parentSkeletonTestClass);
            if ($frontendSkeletonTestClassReflection->isAbstract()
                || $frontendSkeletonTestClassReflection->isInterface()
                || $frontendSkeletonTestClassReflection->isTrait()
            ) {
                continue;
            }
            if (in_array($parentSkeletonTestClass, $this->getIgnoredParentTestClasses(), true)) {
                continue;
            }
            $expectedCalculatorTestClass = \str_replace('\\RulesSkeleton', '\\CalculatorSkeleton', $parentSkeletonTestClass);
            self::assertTrue(
                \class_exists($expectedCalculatorTestClass),
                "Missing test class {$expectedCalculatorTestClass} adopted from parent skeleton test class {$parentSkeletonTestClass}"
            );
            self::assertTrue(
                \is_a($expectedCalculatorTestClass, $parentSkeletonTestClass, true),
                "$expectedCalculatorTestClass should be a child of $parentSkeletonTestClass"
            );
        }
    }

    protected function getIgnoredParentTestClasses(): array
    {
        return [
            DirsTest::class,
            ServicesContainerTest::class,
            HeadTest::class,
            EnvironmentTest::class,
            CurrentWebVersionTest::class,
            SkeletonInjectorComposerPluginTest::class,
            RedirectTest::class,
            HtmlHelperTest::class,
            UsagePolicyTest::class,
            MenuTest::class,
            MainContentTest::class,
            PassContentTest::class,
            RulesMainContentTest::class,
            ConfigurationTest::class,
            CookiesServiceTest::class,
            AbstractPublicFilesTest::class,
            RouterTest::class,
            RequestTest::class,
            ContentIrrelevantParametersFilterTest::class,
            CacheTest::class,
            TableOfContentsTest::class,
            PassingTest::class,
        ];
    }

    protected function getTestingClassesWithoutSut(): array
    {
        return array_map(
            function (string $className) {
                return str_replace('RulesSkeleton', 'CalculatorSkeleton', $className);
            },
            parent::getTestingClassesWithoutSut()
        );
    }
}