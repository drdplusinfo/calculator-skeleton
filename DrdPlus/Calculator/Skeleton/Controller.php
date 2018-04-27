<?php
namespace DrdPlus\Calculator\Skeleton;

use Granam\Strict\Object\StrictObject;

abstract class Controller extends StrictObject
{

    public const DELETE_HISTORY = 'delete_history';
    public const REMEMBER_CURRENT = 'remember_current';

    /** @var array */
    private $currentValues;
    /** @var Memory */
    private $memory;
    /** @var History */
    private $history;

    protected function __construct(string $cookiesPostfix, int $cookiesTtl = null, array $currentValues = null)
    {
        $this->currentValues = $currentValues ?? $_GET;
        $this->memory = $this->createMemory($this->currentValues, $cookiesPostfix, $cookiesTtl);
        $this->history = $this->createHistory($this->currentValues, $cookiesPostfix, $cookiesTtl);
    }

    protected function createMemory(array $values, string $cookiesPostfix, int $cookiesTtl = null): Memory
    {
        return new Memory(
            !empty($_POST[self::DELETE_HISTORY]),
            $values,
            !empty($values[self::REMEMBER_CURRENT]),
            $cookiesPostfix,
            $cookiesTtl
        );
    }

    protected function createHistory(array $values, string $cookiesPostfix, int $cookiesTtl = null): History
    {
        return new History(
            !empty($_POST[self::DELETE_HISTORY]),
            $values,
            !empty($values[self::REMEMBER_CURRENT]),
            $cookiesPostfix,
            $cookiesTtl
        );
    }

    /**
     * @return Memory
     */
    protected function getMemory(): Memory
    {
        return $this->memory;
    }

    /**
     * @return History
     */
    protected function getHistory(): History
    {
        return $this->history;
    }

    public function shouldRemember(): bool
    {
        return $this->getMemory()->shouldRememberCurrent();
    }

    protected function rewriteValueFromRequest(string $name, $values): void
    {
        $this->currentValues[$name] = $values;
        $this->getMemory()->rewrite($name, $values);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getValueFromRequest(string $name)
    {
        if (\array_key_exists($name, $this->currentValues)) {
            return $this->currentValues[$name];
        }

        return $this->getMemory()->getValue($name);
    }

    /**
     * Its almost same as @see getBagEnds, but gives in a flat array BOTH array values AND indexes from given array
     *
     * @param array $values
     * @return array
     */
    protected function toFlatArray(array $values): array
    {
        $flat = [];
        foreach ($values as $index => $value) {
            if (\is_array($value)) {
                $flat[] = $index;
                foreach ($this->toFlatArray($value) as $subItem) {
                    $flat[] = $subItem;
                }
            } else {
                $flat[] = $value;
            }
        }

        return $flat;
    }

    /**
     * Its almost same as @see toFlatArray, but gives array values only, not indexes
     *
     * @param array $values
     * @return array
     */
    protected function getBagEnds(array $values): array
    {
        $bagEnds = [];
        foreach ($values as $value) {
            if (\is_array($value)) {
                foreach ($this->getBagEnds($value) as $subItem) {
                    $bagEnds[] = $subItem;
                }
            } else {
                $bagEnds[] = $value;
            }
        }

        return $bagEnds;
    }

    /**
     * @param array $additionalParameters
     * @return string
     */
    public function getRequestUrl(array $additionalParameters = []): string
    {
        if (!$additionalParameters) {
            return $_SERVER['REQUEST_URI'] ?? '';
        }
        $values = $_GET;
        $values = \array_merge($values, $additionalParameters); // values from GET can be overwritten

        $query = [];
        foreach ($values as $name => $value) {
            foreach ($this->buildUrlParts($name, $value) as $pair) {
                $query[] = $pair;
            }
        }
        $urlParts = \parse_url($_SERVER['REQUEST_URI'] ?? '');

        return $urlParts['scheme'] . '://' . $urlParts['host'] . '/?' . \implode('&', $query);
    }

    /**
     * @param string $name
     * @param array|string $value
     * @return array|string[]
     */
    private function buildUrlParts(string $name, $value): array
    {
        if (!\is_array($value)) {
            return [\urlencode($name) . '=' . \urlencode($value)];
        }
        $pairs = [];
        foreach ((array)$value as $part) {
            foreach ($this->buildUrlParts($name . '[]', $part) as $pair) {
                $pairs[] = $pair;
            }
        }

        return $pairs;
    }

}