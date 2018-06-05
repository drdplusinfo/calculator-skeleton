<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\Tests\FrontendSkeleton\AbstractContentTest;
use PHPUnit\Framework\TestCase;

class TestsTest extends TestCase
{
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
        $parentTestsReferentialClass = $this->getParentTestsReferentialClass();
        self::assertContains(
            'DrdPlus\Tests\\',
            $parentTestsReferentialClass,
            'Given parent tests referential class should be from DrdPlus\Tests namespace'
        );
        $reflectionClass = new \ReflectionClass($this->getParentTestsReferentialClass());
        $parentTestsReferentialClassNamespace = $reflectionClass->getNamespaceName();
        $currentNamespace = $this->getClassNamespace(static::class);
        $parentTestsDir = \dirname($reflectionClass->getFileName());
        $parentTestClasses = $this->getClassesFromDir($parentTestsDir);
        self::assertNotEmpty($parentTestClasses, "No parent test classes found in {$parentTestsDir}");
        foreach ($parentTestClasses as $parentTestClass) {
            if (!\preg_match('~Test$~', $parentTestClass) || (new \ReflectionClass($parentTestClass))->isAbstract()) {
                continue;
            }
            $expectedRulesTestClass = \str_replace($parentTestsReferentialClassNamespace, $currentNamespace, $parentTestClass);
            self::assertTrue(\class_exists($expectedRulesTestClass), "Missing test class {$expectedRulesTestClass} adopting {$parentTestClass}");
            self::assertTrue(\is_a($expectedRulesTestClass, $parentTestClass, true), "$expectedRulesTestClass should be a child of $parentTestClass");
        }
    }

    protected function getParentTestsReferentialClass(): string
    {
        return AbstractContentTest::class;
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

    /**
     * @param string|null $class
     * @return string
     * @throws \ReflectionException
     */
    private function getClassNamespace(string $class): string
    {
        return (new \ReflectionClass($class))->getNamespaceName();
    }
}