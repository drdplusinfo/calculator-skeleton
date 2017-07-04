<?php
namespace DrdPlus\Configurator\Skeleton;

use Granam\Strict\Object\StrictObject;

abstract class Controller extends StrictObject
{

    const DELETE_HISTORY = 'delete_history';
    const REMEMBER_HISTORY = 'remember_history';

    /** @var History */
    private $history;

    protected function __construct(string $cookiesPostfix, int $cookiesTtl = null)
    {
        $this->history = $this->createHistory($cookiesPostfix, $cookiesTtl);
    }

    protected function createHistory(string $cookiesPostfix, int $cookiesTtl = null): History
    {
        return new History(
            $_GET,
            !empty($_POST[self::DELETE_HISTORY]),
            !empty($_GET[self::REMEMBER_HISTORY]),
            $cookiesPostfix,
            $cookiesTtl
        );
    }

    public function shouldRemember(): bool
    {
        return $this->history->shouldRemember();
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getValueFromRequest(string $name)
    {
        if (array_key_exists($name, $_GET)) {
            return $_GET[$name];
        }

        return $this->history->getValue($name);
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
            if (is_array($value)) {
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
            if (is_array($value)) {
                foreach ($this->getBagEnds($value) as $subItem) {
                    $bagEnds[] = $subItem;
                }
            } else {
                $bagEnds[] = $value;
            }
        }

        return $bagEnds;
    }
}