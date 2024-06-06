<?php

namespace App;

class Auth
{
    public static function user($prefix = ''): string
    {
        $jmeno = @$_SESSION['jmeno'];
        return $jmeno ? "$prefix$jmeno" : '';
    }

    // nastav nebo smaž jméno přihlášeného uživatele
    public static function login($jmeno = ''): void
    {
        if ($jmeno)
            $_SESSION['jmeno'] = $jmeno;
    }

    public static function logout(): void
    {
        unset($_SESSION['jmeno']);
    }

    // Je přihlášen uživatel?
    public static function auth(): bool
    {
        return !!self::user();
    }
}