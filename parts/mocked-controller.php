<?php
namespace DrdPlus\Calculators\Rest;

use DrdPlus\CalculatorSkeleton\Controller;
use Granam\Tests\Tools\CalledMethodExistsPass;
use Mockery\Generator\CachingGenerator;
use Mockery\Generator\StringManipulationGenerator;

/** @var string $vendorRoot */
/** @noinspection PhpIncludeInspection */
require_once $vendorRoot . '/autoload.php';

$strictGenerator = StringManipulationGenerator::withDefaultPasses();
// add check if mocked methods exist
$strictGenerator->addPass(new CalledMethodExistsPass());
\Mockery::setGenerator(new CachingGenerator($strictGenerator));
$controller = \Mockery::mock(Controller::class);
$controller->shouldReceive('getCurrentValuesAsHiddenInputs')
    ->andReturn('');
$controller->shouldReceive('getSourceCodeUrl')
    ->andReturn('https://github.com/jaroslavtyc/drd-plus-calculator-skeleton');

return $controller;