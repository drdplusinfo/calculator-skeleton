<?php
namespace DrdPlus\Calculator\Skeleton;

use Granam\Strict\Object\StrictObject;

class Memory extends StrictObject implements \IteratorAggregate
{
    private const CONFIGURATOR_MEMORY = 'configurator_memory';
    private const CONFIGURATOR_MEMORY_TOKEN = 'configurator_memory_token';
    private const FORGOT_MEMORY = 'forgot_configurator_memory';

    /** @var string */
    private $cookiesPostfix;
    /** @var array */
    private $memoryValues = [];
    /** @var int|null */
    private $cookiesTtl;

    public function __construct(
        bool $deletePreviousMemory,
        array $valuesToRemember,
        bool $rememberCurrent,
        string $cookiesPostfix,
        int $cookiesTtl = null
    )
    {
        $this->cookiesPostfix = $cookiesPostfix;
        if ($deletePreviousMemory) {
            $this->deleteMemory();
        }
        $cookiesTtl = $cookiesTtl ?? (new \DateTime('+ 1 year'))->getTimestamp();
        if (\count($valuesToRemember) > 0) {
            if ($rememberCurrent) {
                $this->remember($valuesToRemember, $cookiesTtl);
            } else {
                $this->deleteMemory();
                Cookie::setCookie(self::FORGOT_MEMORY . '-' . $cookiesPostfix, 1, $cookiesTtl);
            }
        } elseif (!$this->cookieMemoryIsValid()) {
            $this->deleteMemory();
        }
        if (!empty($_COOKIE[self::CONFIGURATOR_MEMORY . '-' . $cookiesPostfix])) {
            $memoryValues = \unserialize($_COOKIE[self::CONFIGURATOR_MEMORY . '-' . $cookiesPostfix], ['allowed_classes' => []]);
            if (\is_array($memoryValues)) {
                $this->memoryValues = $memoryValues;
            }
        }
        $this->cookiesTtl = $cookiesTtl;
    }

    protected function remember(array $valuesToRemember, int $cookiesTtl): void
    {
        Cookie::setCookie(self::FORGOT_MEMORY . '-' . $this->cookiesPostfix, null, $cookiesTtl);
        Cookie::setCookie(self::CONFIGURATOR_MEMORY . '-' . $this->cookiesPostfix, \serialize($valuesToRemember), $cookiesTtl);
        Cookie::setCookie(self::CONFIGURATOR_MEMORY_TOKEN . '-' . $this->cookiesPostfix, \md5_file(__FILE__), $cookiesTtl);
    }

    protected function deleteMemory(): void
    {
        Cookie::setCookie(self::CONFIGURATOR_MEMORY_TOKEN . '-' . $this->cookiesPostfix, null);
        Cookie::setCookie(self::CONFIGURATOR_MEMORY . '-' . $this->cookiesPostfix, null);
    }

    private function cookieMemoryIsValid(): bool
    {
        return !empty($_COOKIE[self::CONFIGURATOR_MEMORY_TOKEN . '-' . $this->cookiesPostfix])
            && $_COOKIE[self::CONFIGURATOR_MEMORY_TOKEN . '-' . $this->cookiesPostfix] === \md5_file(__FILE__);
    }

    public function shouldForgotMemory(): bool
    {
        return !empty($_COOKIE[self::FORGOT_MEMORY . '-' . $this->cookiesPostfix]);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getValue(string $name)
    {
        if (\array_key_exists($name, $this->memoryValues) && $this->cookieMemoryIsValid()) {
            return $this->memoryValues[$name];
        }

        return null;
    }

    /**
     * @param string $name
     * @param $values
     */
    public function rewrite(string $name, $values): void
    {
        $this->memoryValues[$name] = $values;
        $this->remember($this->memoryValues, $this->cookiesTtl);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->memoryValues);
    }

}