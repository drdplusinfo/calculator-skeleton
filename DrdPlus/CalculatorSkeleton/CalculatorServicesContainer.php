<?php
declare(strict_types = 1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Git;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\RulesSkeleton\ServicesContainer;
use DrdPlus\RulesSkeleton\WebVersions;
use DrdPlus\RulesSkeletonWeb\RulesWebContent;

/**
 * @method CalculatorConfiguration getConfiguration()
 * @method CalculatorDirs getDirs()
 */
class CalculatorServicesContainer extends ServicesContainer
{

	/** @var CalculatorBody */
	private $calculatorBody;

	/** @var CalculatorRequest */
	private $calculatorRequest;

	/** @var Memory */
	private $memory;

	/** @var CurrentValues */
	private $currentValues;

	/** @var History */
	private $history;

	/** @var CalculatorContent */
	private $calculatorContent;

	/** @var RulesWebContent */
	private $calculatorWebContent;

	/** @var CalculatorWebVersions */
	private $calculatorWebVersions;

	/** @var GitReader */
	private $gitReader;

	public function __construct(CalculatorConfiguration $calculatorConfiguration, HtmlHelper $htmlHelper)
	{
		parent::__construct($calculatorConfiguration, $htmlHelper);
	}

	public function getCalculatorBody(): CalculatorBody
	{
		if ($this->calculatorBody === null) {
			$this->calculatorBody = new CalculatorBody($this->getWebFiles(), $this->getRequest());
		}

		return $this->calculatorBody;
	}

	/**
	 * @return Request|CalculatorRequest
	 */
	public function getRequest(): Request
	{
		if ($this->calculatorRequest === null) {
			$this->calculatorRequest = new CalculatorRequest($this->getBotParser());
		}

		return $this->calculatorRequest;
	}

	public function getMemory(): Memory
	{
		if ($this->memory === null) {
			$this->memory = new Memory(
				$this->getCookiesService(),
				$this->getRequest()->isRequestedHistoryDeletion(),
				$this->getRequest()->getValuesFromGet(),
				$this->getRequest()->isRequestedRememberCurrent(),
				$this->getConfiguration()->getCookiesPostfix(),
				$this->getConfiguration()->getCookiesTtl()
			);
		}

		return $this->memory;
	}

	public function getCurrentValues(): CurrentValues
	{
		if ($this->currentValues === null) {
			$this->currentValues = new CurrentValues($this->getRequest()->getValuesFromGet(), $this->getMemory());
		}

		return $this->currentValues;
	}

	public function getHistory(): History
	{
		if ($this->history === null) {
			$this->history = new History(
				$this->getCookiesService(),
				$this->getRequest()->isRequestedHistoryDeletion(),
				$this->getRequest()->getValuesFromGet(),
				$this->getRequest()->isRequestedRememberCurrent(),
				$this->getConfiguration()->getCookiesPostfix(),
				$this->getConfiguration()->getCookiesTtl()
			);
		}

		return $this->history;
	}

	public function getCalculatorContent(): CalculatorContent
	{
		if ($this->calculatorContent === null) {
			$this->calculatorContent = new CalculatorContent(
				$this->getRulesWebContent(),
				$this->getHtmlHelper(),
				$this->getWebVersions(),
				$this->getMenu(),
				$this->getWebCache()
			);
		}

		return $this->calculatorContent;
	}

	public function getRulesWebContent(): RulesWebContent
	{
		if ($this->calculatorWebContent === null) {
			$this->calculatorWebContent = new RulesWebContent(
				$this->getHtmlHelper(),
				$this->getHead(),
				$this->getCalculatorBody()
			);
		}

		return $this->calculatorWebContent;
	}

	public function getWebVersions(): WebVersions
	{
		if ($this->calculatorWebVersions === null) {
			$this->calculatorWebVersions = new CalculatorWebVersions(
				$this->getConfiguration(),
				$this->getRequest(),
				$this->getGit()
			);
		}
		return $this->calculatorWebVersions;
	}

	public function getGit(): Git
	{
		if ($this->gitReader === null) {
			$this->gitReader = new GitReader();
		}
		return $this->gitReader;
	}
}