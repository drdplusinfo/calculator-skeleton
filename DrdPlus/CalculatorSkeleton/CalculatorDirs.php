<?php
declare(strict_types = 1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Dirs;

class CalculatorDirs extends Dirs
{

	public function getVersionRoot(string $forVersion): string
	{
		return $this->getProjectRoot();
	}

	public function getVersionWebRoot(string $forVersion): string
	{
		return $this->getProjectRoot() . '/web';
	}
}