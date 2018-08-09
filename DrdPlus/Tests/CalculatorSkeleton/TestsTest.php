<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\Tests\FrontendSkeleton\FrontendControllerTest;
use PHPUnit\Framework\TestCase;

class TestsTest extends TestCase
{
    use Partials\AbstractContentTestTrait;

    /**
     * @test
     * @throws \ReflectionException
     */
    public function Every_test_lives_in_drd_plus_tests_namespace(): void
    {
        $reflectionClass = new \ReflectionClass(static::class);
        $testsDir = \dirname($reflectionClass->getFileName());
        $testClasses = $this->getClassesFromDir($testsDir);
        self::assertNotEmpty($testClasses, "No test classes found in {$testsDir}");
        foreach ($testClasses as $testClass) {
            self::assertStringStartsWith(
                'DrdPlus\\Tests',
                (new \ReflectionClass($testClass))->getNamespaceName(),
                "Class {$testClass} should be in DrdPlus\\Test namespace"
            );
        }
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function All_frontend_skeleton_tests_are_used(): void
    {
        $reflectionClass = new \ReflectionClass(\DrdPlus\Tests\FrontendSkeleton\ContentTest::class);
        $frontendSkeletonDir = \dirname($reflectionClass->getFileName());
        foreach ($this->getClassesFromDir($frontendSkeletonDir) as $frontendSkeletonTestClass) {
            if (\is_a($frontendSkeletonTestClass, \Throwable::class, true)
                || \is_a($frontendSkeletonTestClass, FrontendControllerTest::class, true) // it is solved via CalculatorController
            ) {
                continue;
            }
            $frontendSkeletonTestClassReflection = new \ReflectionClass($frontendSkeletonTestClass);
            if ($frontendSkeletonTestClassReflection->isAbstract()
                || $frontendSkeletonTestClassReflection->isInterface()
                || $frontendSkeletonTestClassReflection->isTrait()
            ) {
                continue;
            }
            $expectedCalculatorTestClass = \str_replace('\\FrontendSkeleton', '\\CalculatorSkeleton', $frontendSkeletonTestClass);
            self::assertTrue(
                \class_exists($expectedCalculatorTestClass),
                "Missing test class {$expectedCalculatorTestClass} adopted from frontend skeleton test class {$frontendSkeletonTestClass}"
            );
            self::assertTrue(
                \is_a($expectedCalculatorTestClass, $frontendSkeletonTestClass, true),
                "$expectedCalculatorTestClass should be a child of $frontendSkeletonTestClass"
            );
        }
    }

    private function getClassesFromDir(string $dir): array
    {
        $classes = [];
        foreach (\scandir($dir, SCANDIR_SORT_NONE) as $folder) {
            if ($folder === '.' || $folder === '..') {
                continue;
            }
            if (!\preg_match('~\.php$~', $folder)) {
                if (\is_dir($dir . '/' . $folder)) {
                    foreach ($this->getClassesFromDir($dir . '/' . $folder) as $class) {
                        $classes[] = $class;
                    }
                }
                continue;
            }
            self::assertNotEmpty(
                \preg_match('~(?<className>DrdPlus/[^/].+)\.php~', $dir . '/' . $folder, $matches),
                "DrdPlus class name has not been determined from $dir/$folder"
            );
            $classes[] = \str_replace('/', '\\', $matches['className']);
        }

        return $classes;
    }
}