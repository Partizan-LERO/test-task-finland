<?php


namespace Framework\Console;


class ConsoleOutput
{

    public static function success(string $text)
    {
        echo "\033[32m $text \033[0m\n";
    }

    public static function error(string $text)
    {
        echo "\033[31m $text \033[0m\n";
    }

    public static function warning(string $text)
    {
        echo "\033[33m $text \033[0m\n";
    }

    public static function info(string $text)
    {
        echo "\033[36m $text \033[0m\n";
    }

    public static function primary(string $text)
    {
        echo "$text \n";
    }
}
