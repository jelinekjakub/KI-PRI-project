<?php

namespace App;

use mysqli, mysqli_result;


class Database
{
    private static $database = null;

    // Initialize the database connection
    private static function init(): void
    {
        if (is_null(self::$database))
        {
            self::$database = mysqli_connect("localhost", "root", "", "mixolog");
            if (self::$database->connect_error)
            {
                die("Připojení k databázi selhalo: " . self::$database->connect_error);
            }
        }
    }

    public static function query(string $query): bool|mysqli_result
    {
        self::init();
        return self::$database->query($query);
    }

    public static function escape(string $value): string
    {
        self::init();
        return "'" . mysqli_real_escape_string(self::$database, $value) . "'";
    }

    public static function auth(string $username, string $password): bool
    {
        $username = self::escape($username);
        $password = self::escape($password);

        if ($result = self::query("SELECT * FROM users WHERE username = $username AND password = $password"))
        {
            if ($result->num_rows)
            {
                [[$id]] = $result->fetch_all();
                if ($id) return true;
            }
        }

        return false;
    }

    public static function readDrink(string $drink): int
    {
        $drink = self::escape($drink);

        self::query("INSERT INTO drinky (nazev, precteno) VALUES ($drink, 1) ON DUPLICATE KEY UPDATE precteno = precteno + 1");
        $result = self::query("SELECT precteno FROM drinky WHERE nazev = $drink");
        [[$read]] = $result->fetch_all();

        return $read;
    }
}
