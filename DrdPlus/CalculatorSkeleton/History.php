<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use Granam\Strict\Object\StrictObject;

class History extends StrictObject
{
    public const CALCULATOR_HISTORY = 'configurator_history';

    public static function createCookiesTtlDate(?int $cookiesTtl): \DateTimeImmutable
    {
        return $cookiesTtl !== null
            ? new \DateTimeImmutable('@' . (\time() + $cookiesTtl))
            : new \DateTimeImmutable('+ 1 year');
    }

    public static function createStorageKey(string $postfix): string
    {
        return self::CALCULATOR_HISTORY . '-' . $postfix;
    }

    /** @var array */
    private $historyValues;
    /** @var bool */
    private $cookiesStorage;

    public function __construct(CookiesStorage $cookiesStorage)
    {
        $this->cookiesStorage = $cookiesStorage;
    }

    public function saveHistory(array $valuesToRemember): void
    {
        $this->loadsHistoryValues(); // loads previous history as they would be overwritten now
        $this->cookiesStorage->storeValues($valuesToRemember);
    }

    private function loadsHistoryValues(): void
    {
        $this->historyValues = $this->getHistoryValues();
    }

    public function deleteHistory(): void
    {
        $this->cookiesStorage->deleteAll();
    }

    public function getValue(string $name)
    {
        return $this->getHistoryValues()[$name] ?? null;
    }

    public function getHistoryValues(): array
    {
        if ($this->historyValues === null) {
            $this->loadsHistoryValues();
        }
        return $this->historyValues;
    }
}