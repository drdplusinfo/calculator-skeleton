<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\CookiesService;
use Granam\Strict\Object\StrictObject;

class CookiesStorage extends StrictObject
{
    /**
     * @var CookiesService
     */
    private $cookiesService;
    /**
     * @var string
     */
    private $storageKey;
    /**
     * @var \DateTimeInterface|null
     */
    private $cookiesTtlDate;
    /**
     * @var array
     */
    private $values;

    public function __construct(CookiesService $cookiesService, string $storageKey, ?\DateTimeInterface $cookiesTtlDate)
    {
        $this->cookiesService = $cookiesService;
        $this->storageKey = $storageKey;
        $this->cookiesTtlDate = $cookiesTtlDate;
    }

    public function storeValues(array $valuesToRemember): void
    {
        $this->cookiesService->setCookie($this->storageKey, \serialize($valuesToRemember), false, $this->cookiesTtlDate);
        $this->values = $valuesToRemember;
    }

    public function deleteAll(): void
    {
        $this->cookiesService->deleteCookie($this->storageKey);
        $this->values = [];
    }

    public function getValue(string $name)
    {
        return $this->getValues()[$name] ?? null;
    }

    public function getValues()
    {
        if ($this->values === null) {
            $this->values = $this->fetchMemoryValues();
        }
        return $this->values;
    }

    private function fetchMemoryValues(): array
    {
        $encodedValues = $this->cookiesService->getCookie($this->storageKey);
        if ($encodedValues === null) {
            return [];
        }
        $values = \unserialize($encodedValues, ['allowed_classes' => []]);
        if (\is_array($values)) {
            return $values;
        }
        return [];
    }
}