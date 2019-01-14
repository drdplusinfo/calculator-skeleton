<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\CalculatorSkeleton;

class CalculatorWebVersionsTest extends \DrdPlus\Tests\RulesSkeleton\WebVersionsTest
{

	use Partials\AbstractContentTestTrait;

	/**
	 * @test
	 */
	public function I_can_update_web_version_even_if_not_yet_fetched_locally(): void
	{
		self::assertFalse(false, 'Calculator web files can not be updated separately');
	}

	/**
	 * @test
	 */
	public function I_can_update_already_fetched_web_version(): void
	{
		self::assertFalse(false, 'Calculator web files can not be updated separately');
	}

	public function I_can_not_update_non_existing_web_version(): void
	{
		self::assertFalse(false, 'Calculator web files can not be updated separately');
	}

	/**
	 * @throws \DrdPlus\CalculatorSkeleton\Exceptions\UpdateForbiddenForCalculatorWebFiles
	 */
	public function I_can_not_update_web_version(): void
	{
		$this->createWebVersions()->update('foo');
	}
}