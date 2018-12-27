<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\FrontendSkeleton\Configuration;
use DrdPlus\FrontendSkeleton\Dirs;
use Granam\Integer\Tools\ToInteger;

class CalculatorConfiguration extends Configuration
{
    public const SOURCE_CODE_URL = 'source_code_url';
    public const COOKIES_POSTFIX = 'cookies_postfix';
    public const COOKIES_TTL = 'cookies_ttl';

    public function __construct(Dirs $dirs, array $settings)
    {
        $this->sanitizeCookiesTtl($settings);
        $this->guardValidSourceCodeUrl($settings);
        $this->guardValidCookiesPostfix($settings);
        parent::__construct($dirs, $settings);
    }

    /**
     * @param array $settings
     * @throws \DrdPlus\CalculatorSkeleton\Exceptions\SourceCodeUrlIsNotValid
     */
    protected function guardValidSourceCodeUrl(array $settings): void
    {
        if (!\filter_var($settings[self::WEB][self::SOURCE_CODE_URL] ?? '', \FILTER_VALIDATE_URL)) {
            throw new Exceptions\SourceCodeUrlIsNotValid(
                sprintf(
                    'Given source code URL is not a valid one in web.%s, got %s',
                    self::SOURCE_CODE_URL,
                    array_key_exists(self::SOURCE_CODE_URL, $settings)
                        ? "'{$settings[self::WEB][self::SOURCE_CODE_URL]}'"
                        : 'nothing'
                )
            );
        }
    }

    /**
     * @param array $settings
     * @throws \DrdPlus\CalculatorSkeleton\Exceptions\SourceCodeUrlIsNotValid
     */
    protected function guardValidCookiesPostfix(array $settings): void
    {
        if (($settings[self::WEB][self::COOKIES_POSTFIX] ?? '') === '') {
            throw new Exceptions\SourceCodeUrlIsNotValid(
                sprintf(
                    'Given cookies postfix are empty in web.%s, got %s',
                    self::COOKIES_POSTFIX,
                    array_key_exists(self::COOKIES_POSTFIX, $settings)
                        ? "'{$settings[self::WEB][self::COOKIES_POSTFIX]}'"
                        : 'nothing'
                )
            );
        }
    }

    /**
     * @param array $settings
     * @throws \DrdPlus\CalculatorSkeleton\Exceptions\InvalidCookiesTtl
     */
    protected function sanitizeCookiesTtl(array &$settings): void
    {
        try {
            $settings[self::WEB][self::COOKIES_TTL] = ToInteger::toPositiveIntegerOrNull($settings[self::WEB][self::COOKIES_TTL] ?? null);
        } catch (\Granam\Integer\Tools\Exceptions\Runtime $runtime) {
            throw new Exceptions\InvalidCookiesTtl(
                'Expected positive integer or null, got ' . var_export($settings[self::WEB][self::COOKIES_TTL] ?? null, true)
            );
        }
    }

    public function getSourceCodeUrl(): string
    {
        return $this->getSettings()[self::WEB][self::SOURCE_CODE_URL];
    }

    public function getCookiesPostfix(): string
    {
        return $this->getSettings()[self::WEB][self::COOKIES_POSTFIX];
    }

    public function getCookiesTtl(): ?int
    {
        return $this->getSettings()[self::WEB][self::COOKIES_TTL];

    }
}