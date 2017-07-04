<?php
namespace DrdPlus\Configurator\Skeleton;

use Granam\Strict\Object\StrictObject;

class History extends StrictObject
{
    private const CONFIGURATOR_HISTORY = 'configurator_history';
    private const CONFIGURATOR_HISTORY_TOKEN = 'configurator_history_token';
    private const FORGOT = 'forgot_configurator_history';

    /** @var string */
    private $cookiesPostfix;
    /** @var array */
    private $historyValues = [];

    public function __construct(
        array $valuesToRemember,
        bool $deleteFightHistory,
        bool $remember,
        string $cookiesPostfix,
        int $cookiesTtl = null
    )
    {
        $this->cookiesPostfix = $cookiesPostfix;
        if ($deleteFightHistory) {
            $this->deleteHistory();
        }
        if (count($valuesToRemember) > 0) {
            $cookiesTtl = $cookiesTtl ?? (new \DateTime('+ 1 year'))->getTimestamp();
            if ($remember) {
                $this->remember($valuesToRemember, $cookiesTtl);
            } else {
                $this->deleteHistory();
                Cookie::setCookie(self::FORGOT . '-' . $cookiesPostfix, 1, $cookiesTtl);
            }
        } elseif (!$this->cookieHistoryIsValid()) {
            $this->deleteHistory();
        }
        if (!empty($_COOKIE[self::CONFIGURATOR_HISTORY . '-' . $cookiesPostfix])) {
            $this->historyValues = unserialize($_COOKIE[self::CONFIGURATOR_HISTORY . '-' . $cookiesPostfix], ['allowed_classes' => []]);
            if (!is_array($this->historyValues)) {
                $this->historyValues = [];
            }
        }
    }

    protected function remember(array $valuesToRemember, int $cookiesTtl): void
    {
        Cookie::setCookie(self::FORGOT . '-' . $this->cookiesPostfix, null, $cookiesTtl);
        Cookie::setCookie(self::CONFIGURATOR_HISTORY . '-' . $this->cookiesPostfix, serialize($valuesToRemember), $cookiesTtl);
        Cookie::setCookie(self::CONFIGURATOR_HISTORY_TOKEN . '-' . $this->cookiesPostfix, md5_file(__FILE__), $cookiesTtl);
    }

    protected function deleteHistory(): void
    {
        Cookie::setCookie(self::CONFIGURATOR_HISTORY_TOKEN . '-' . $this->cookiesPostfix, null);
        Cookie::setCookie(self::CONFIGURATOR_HISTORY . '-' . $this->cookiesPostfix, null);
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
    public function getValue(string $name)
    {
        if (array_key_exists($name, $_GET)) {
            return $_GET[$name];
        }
        if (array_key_exists($name, $this->historyValues) && $this->cookieHistoryIsValid()) {
            return $this->historyValues[$name];
        }

        return null;
    }
}