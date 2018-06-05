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
    public function All_frontend_skeleton_tests_are_used(): void
    {
        $reflectionClass = new \ReflectionClass(AbstractContentTest::class);
        $frontendSkeletonNamespace = $reflectionClass->getNamespaceName();
        $currentNamespace = $this->getClassNamespace(static::class);
        $frontendSkeletonDir = \dirname($reflectionClass->getFileName());
        foreach ($this->getClassesFromDir($frontendSkeletonDir) as $frontendSkeletonTestClass) {
            if (!($frontendSkeletonTestClass instanceof TestCase) || (new \ReflectionClass($frontendSkeletonTestClass))->isAbstract()) {
                continue;
            }
            $expectedRulesTestClass = \str_replace($frontendSkeletonNamespace, $currentNamespace, $frontendSkeletonTestClass);
            self::assertTrue(\class_exists($expectedRulesTestClass), "Missing test class {$expectedRulesTestClass} adopting {$frontendSkeletonTestClass}");
            self::assertTrue(\is_a($expectedRulesTestClass, $frontendSkeletonTestClass, true), "$expectedRulesTestClass should be a child of $frontendSkeletonTestClass");
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