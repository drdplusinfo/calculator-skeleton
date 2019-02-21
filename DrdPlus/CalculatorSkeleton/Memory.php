<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use Granam\Strict\Object\StrictObject;

class Memory extends StrictObject
{
    private const CALCULATOR_MEMORY = 'configurator_memory';

    public static function createCookiesTtlDate(?int $cookiesTtl): ?\DateTimeImmutable
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $cookiesTtl !== null
            ? new \DateTimeImmutable('@' . (\time() + $cookiesTtl))
            : null; // at the end of session
    }

    public static function createStorageKey(string $postfix): string
    {
        return self::CALCULATOR_MEMORY . '-' . $postfix;
    }

    /** @var CookiesStorage */
    private $cookiesStorage;

    public function __construct(CookiesStorage $cookiesStorage)
    {
        $this->cookiesStorage = $cookiesStorage;
    }

    public function saveMemory(array $valuesToRemember): void
    {
        $this->cookiesStorage->storeValues($valuesToRemember);
    }

    public function deleteMemory(): void
    {
        $this->cookiesStorage->deleteAll();
    }

    public function getValue(string $name)
    {
        return $this->cookiesStorage->getValues()[$name] ?? null;
    }
}