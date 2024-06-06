<?php

namespace App;

class Navigation
{
    private static array $pages = [
    '/' => 'Home',
    '/login.php' => 'Přihlášení',
    '/drinks.php' => 'Receptář',
    ];

    public static function getPages(): array
    {
        // přihlášený uživatel smí nahrávat recepty
        if (Auth::auth())
        {
            self::$pages['/upload.php'] = 'Nahrát';
            self::$pages['/create.php'] = 'Vytvořit';
        }

        return self::$pages;
    }
}
