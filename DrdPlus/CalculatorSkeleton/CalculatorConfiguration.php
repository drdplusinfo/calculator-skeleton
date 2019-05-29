<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\RulesSkeleton\Dirs;
use Granam\Integer\Tools\ToInteger;

/**
 * @method static CalculatorConfiguration createFromYml(Dirs $dirs)
 */
class CalculatorConfiguration extends Configuration
{
    public const COOKIES_POSTFIX = 'cookies_postfix';
    public const COOKIES_TTL = 'cookies_ttl';

    public function __construct(Dirs $dirs, array $settings)
    {
        $this->sanitizeCookiesTtl($settings);
        $this->guardValidCookiesPostfix($settings);
        parent::__construct($dirs, $settings);
    }

    /**
     * @param array $settings
     * @throws \DrdPlus\CalculatorSkeleton\Exceptions\InvalidCookiesTtl
     */
    protected function sanitizeCookiesTtl(array &$settings): void
    {
        try {
            $settings[static::WEB][static::COOKIES_TTL] = ToInteger::toPositiveIntegerOrNull($settings[static::WEB][static::COOKIES_TTL] ?? null);
        } catch (\Granam\Integer\Tools\Exceptions\Runtime $runtime) {
            throw new Exceptions\InvalidCookiesTtl(
                sprintf(
                    'Expected positive integer or null, got %s (%s)',
                    array_key_exists(static::COOKIES_TTL, $settings[static::WEB])
                        ? var_export($settings[static::WEB][static::COOKIES_TTL], true)
                        : 'nothing',
                    $runtime->getMessage()
                )
            );
        }
    }

    /**
     * @param array $settings
     * @throws \DrdPlus\CalculatorSkeleton\Exceptions\InvalidCookiesPostfix
     */
    protected function guardValidCookiesPostfix(array $settings): void
    {
        if (($settings[static::WEB][static::COOKIES_POSTFIX] ?? '') === '') {
            throw new Exceptions\InvalidCookiesPostfix(
                sprintf(
                    'Given cookies postfix are empty in %s.%s, got %s',
                    static::WEB,
                    static::COOKIES_POSTFIX,
                    array_key_exists(static::COOKIES_POSTFIX, $settings[static::WEB])
                        ? var_export($settings[static::WEB][static::COOKIES_POSTFIX], true)
                        : 'nothing'
                )
            );
        }
    }

    public function getCookiesPostfix(): string
    {
        return $this->getSettings()[static::WEB][static::COOKIES_POSTFIX];
    }

    public function getCookiesTtl(): ?int
    {
        return $this->getSettings()[static::WEB][static::COOKIES_TTL];

    }
}