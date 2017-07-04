<?php
namespace DrdPlus\Configurator\Skeleton;

use Granam\Strict\Object\StrictObject;

abstract class Controller extends StrictObject
{

    private const DELETE_CONFIGURATOR_HISTORY = 'delete_configurator_history';
    private const CONFIGURATOR_HISTORY = 'configurator_history';
    private const CONFIGURATOR_HISTORY_TOKEN = 'configurator_history_token';
    private const REMEMBER = 'remember';
    private const FORGOT = 'forgot';

    /** @var array */
    private $history = [];
    /**
     * @var string
     */
    private $cookiesPostfix;

    protected function __construct(string $cookiesPostfix, int $cookiesTtl = null)
    {
        $this->cookiesPostfix = $cookiesPostfix;
        if (!empty($_POST[self::DELETE_CONFIGURATOR_HISTORY . '-' . $cookiesPostfix])) {
            $this->deleteHistory();
            header('Location: /', true, 301);
            exit;
        }
        $afterYear = $cookiesTtl ?? (new \DateTime('+ 1 year'))->getTimestamp();
        if (!empty($_GET)) {
            if (!empty($_GET[self::REMEMBER . '-' . $cookiesPostfix])) {
                $this->setCookie(self::FORGOT . '-' . $cookiesPostfix, null, $afterYear);
                $this->setCookie(self::CONFIGURATOR_HISTORY . '-' . $cookiesPostfix, serialize($_GET), $afterYear);
                $this->setCookie(self::CONFIGURATOR_HISTORY_TOKEN . '-' . $cookiesPostfix, md5_file(__FILE__), $afterYear);
            } else {
                $this->deleteHistory();
                $this->setCookie(self::FORGOT . '-' . $cookiesPostfix, 1, $afterYear);
            }
        } elseif (!$this->cookieHistoryIsValid()) {
            $this->deleteHistory();
        }
        if (!empty($_COOKIE[self::CONFIGURATOR_HISTORY . '-' . $cookiesPostfix])) {
            $this->history = unserialize($_COOKIE[self::CONFIGURATOR_HISTORY . '-' . $cookiesPostfix], ['allowed_classes' => []]);
            if (!is_array($this->history)) {
                $this->history = [];
            }
        }
    }

    protected function deleteHistory(): void
    {
        $this->setCookie(self::CONFIGURATOR_HISTORY_TOKEN . '-' . $this->cookiesPostfix, null);
        $this->setCookie(self::CONFIGURATOR_HISTORY . '-' . $this->cookiesPostfix, null);
    }

    protected function setCookie(string $name, $value, int $expire = 0): void
    {
        setcookie(
            $name,
            $value,
            $expire,
            '/',
            '',
            !empty($_SERVER['HTTPS']), // secure only ?
            true // http only
        );
        $_COOKIE[$name] = $value;
    }

    private function cookieHistoryIsValid(): bool
    {
        return !empty($_COOKIE[self::CONFIGURATOR_HISTORY_TOKEN . '-' . $this->cookiesPostfix])
            && $_COOKIE[self::CONFIGURATOR_HISTORY_TOKEN . '-' . $this->cookiesPostfix] === md5_file(__FILE__);
    }

    public function shouldRemember(): bool
    {
        return empty($_COOKIE[self::FORGOT . '-' . $this->cookiesPostfix]);
    }

    /**
     * @param string $name
     * @return mixed
     */
    protected function getValueFromRequest(string $name)
    {
        if (array_key_exists($name, $_GET)) {
            return $_GET[$name];
        }
        if (array_key_exists($name, $this->history) && $this->cookieHistoryIsValid()) {
            return $this->history[$name];
        }

        return null;
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

    /**
     * @return string
     */
    public function getCookiesPostfix(): string
    {
        return $this->cookiesPostfix;
    }

    public function getDeleteHistoryInputName(): string
    {
        return self::DELETE_CONFIGURATOR_HISTORY . '-' . $this->cookiesPostfix;
    }
}