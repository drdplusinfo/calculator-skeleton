<?php
declare(strict_types = 1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Git;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\RulesSkeleton\WebVersions;

class CalculatorWebVersions extends WebVersions
{

	public function __construct(CalculatorConfiguration $configuration, Request $request, Git $git)
	{
		parent::__construct($configuration, $request, $git);
	}

	public function update(string $minorVersion): array
	{
		throw new Exceptions\UpdateForbiddenForCalculatorWebFiles(
			"Can not update web files version '$minorVersion' as calculator does not have separated web files repository"
		);
	}
}