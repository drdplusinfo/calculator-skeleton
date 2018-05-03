<?php
namespace DrdPlus\Calculator\Skeleton;

use Granam\Strict\Object\StrictObject;

class History extends StrictObject
{
    private const CONFIGURATOR_HISTORY = 'configurator_history';
    private const CONFIGURATOR_HISTORY_TOKEN = 'configurator_history_token';
    private const FORGOT_HISTORY = 'forgot_configurator_history';

    /** @var string */
    private $cookiesPostfix;
    /** @var array */
    private $historyValues = [];

    public function __construct(
        bool $deletePreviousHistory,
        array $valuesToRemember,
        bool $rememberHistory,
        string $cookiesPostfix,
        int $cookiesTtl = null
    )
    {
        $this->cookiesPostfix = $cookiesPostfix;
        if ($deletePreviousHistory) {
            $this->deleteHistory();
        }
        if (\count($valuesToRemember) > 0) {
            if (!$rememberHistory) {
                $this->deleteHistory();
                $cookiesTtl = $cookiesTtl ?? (new \DateTime('+ 1 year'))->getTimestamp();
                Cookie::setCookie(self::FORGOT_HISTORY . '-' . $cookiesPostfix, 1, $cookiesTtl);
            }
        } elseif (!$this->cookieHistoryIsValid()) {
            $this->deleteHistory();
        }
        if (!empty($_COOKIE[self::CONFIGURATOR_HISTORY . '-' . $cookiesPostfix])) {
            $historyValues = \unserialize($_COOKIE[self::CONFIGURATOR_HISTORY . '-' . $cookiesPostfix], ['allowed_classes' => []]);
            if (\is_array($historyValues)) {
                $this->historyValues = $historyValues;
            }
        }
        if ($rememberHistory && \count($valuesToRemember) > 0) {
            $cookiesTtl = $cookiesTtl ?? (new \DateTime('+ 1 year'))->getTimestamp();
            $this->remember($valuesToRemember, $cookiesTtl);
        }
    }

    protected function remember(array $valuesToRemember, int $cookiesTtl): void
    {
        Cookie::setCookie(self::FORGOT_HISTORY . '-' . $this->cookiesPostfix, null, $cookiesTtl);
        Cookie::setCookie(self::CONFIGURATOR_HISTORY . '-' . $this->cookiesPostfix, \serialize($valuesToRemember), $cookiesTtl);
        Cookie::setCookie(self::CONFIGURATOR_HISTORY_TOKEN . '-' . $this->cookiesPostfix, \md5_file(__FILE__), $cookiesTtl);
    }

    protected function deleteHistory(): void
    {
        Cookie::setCookie(self::CONFIGURATOR_HISTORY_TOKEN . '-' . $this->cookiesPostfix, null);
        Cookie::setCookie(self::CONFIGURATOR_HISTORY . '-' . $this->cookiesPostfix, null);
    }

    private function cookieHistoryIsValid(): bool
    {
        return !empty($_COOKIE[self::CONFIGURATOR_HISTORY_TOKEN . '-' . $this->cookiesPostfix])
            && $_COOKIE[self::CONFIGURATOR_HISTORY_TOKEN . '-' . $this->cookiesPostfix] === \md5_file(__FILE__);
    }

    public function shouldForgotHistory(): bool
    {
        return !empty($_COOKIE[self::FORGOT_HISTORY . '-' . $this->cookiesPostfix]);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getValue(string $name)
    {
        if (\array_key_exists($name, $this->historyValues) && $this->cookieHistoryIsValid()) {
            return $this->historyValues[$name];
        }

        return null;
    }
}