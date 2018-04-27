<?php
namespace DrdPlus\Calculator\Skeleton;

use Granam\Strict\Object\StrictObject;

class Cookie extends StrictObject
{
    public static function setCookie(string $name, $value, int $expire = 0): bool
    {
        $result = \setcookie(
            $name,
            $value,
            $expire,
            '/',
            '',
            !empty($_SERVER['HTTPS']), // secure only ?
            true // http only
        );
        if ($result) {
            if ($value === null) {
                unset($_COOKIE[$name]);
            } else {
                $_COOKIE[$name] = $value;
            }
        }

        return $result;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public static function getCookie(string $name)
    {
        return $_COOKIE[$name] ?? null;
    }

    public static function deleteCookie(string $name): bool
    {
        return self::setCookie($name, null);
    }
}