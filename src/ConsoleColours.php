<?php

declare(strict_types=1);

namespace Brunty\Cigar;

/*
 * Yeah British English spelling, DEAL WITH IT
 *
 * Also these are foreground colors
 */
enum ConsoleColours: int
{
    private const ESCAPE_CHAR = "\e";

    case RESET = 0;
    case RED = 31;
    case GREEN = 32;
    case YELLOW = 33;
    case CYAN = 36;
    case GREY = 90;

    public static function red(): string
    {
        return self::consoleCode(self::RED);
    }

    public static function green(): string
    {
        return self::consoleCode(self::GREEN);
    }

    public static function yellow(): string
    {
        return self::consoleCode(self::YELLOW);
    }

    public static function cyan(): string
    {
        return self::consoleCode(self::CYAN);
    }

    public static function grey(): string
    {
        return self::consoleCode(self::GREY);
    }

    public static function reset(): string
    {
        return self::consoleCode(self::RESET);
    }

    private static function consoleCode(self $type): string
    {
        return self::ESCAPE_CHAR . '[' . $type->value . 'm';
    }
}
