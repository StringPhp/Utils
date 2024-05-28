<?php

namespace StringPhp\Utils;

use BadMethodCallException;

/**
 * Color codes with magic ANSI escape functions
 *
 * @method static string RED(string $content)
 * @method static string RED_INVERTED(string $content)
 * @method static string ORANGE(string $content)
 * @method static string ORANGE_INVERTED(string $content)
 * @method static string YELLOW(string $content)
 * @method static string YELLOW_INVERTED(string $content)
 * @method static string OLIVE(string $content)
 * @method static string OLIVE_INVERTED(string $content)
 * @method static string GREEN(string $content)
 * @method static string GREEN_INVERTED(string $content)
 * @method static string TEAL(string $content)
 * @method static string TEAL_INVERTED(string $content)
 * @method static string BLUE(string $content)
 * @method static string BLUE_INVERTED(string $content)
 * @method static string VIOLET(string $content)
 * @method static string VIOLET_INVERTED(string $content)
 * @method static string PURPLE(string $content)
 * @method static string PURPLE_INVERTED(string $content)
 * @method static string PINK(string $content)
 * @method static string PINK_INVERTED(string $content)
 * @method static string BROWN(string $content)
 * @method static string BROWN_INVERTED(string $content)
 * @method static string GREY(string $content)
 * @method static string GREY_INVERTED(string $content)
 * @method static string BLACK(string $content)
 * @method static string BLACK_INVERTED(string $content)
 */
final class Colors
{
    public const string RED = '#ff695e';
    public const string RED_INVERTED = '#db2828';
    public const string ORANGE = '#ff851b';
    public const string ORANGE_INVERTED = '#f2711c';
    public const string YELLOW = '#ffe21f';
    public const string YELLOW_INVERTED = '#fbbd08';
    public const string OLIVE = '#d9e778';
    public const string OLIVE_INVERTED = '#b5cc18';
    public const string GREEN = '#2ecc40';
    public const string GREEN_INVERTED = '#21ba45';
    public const string TEAL = '#6dffff';
    public const string TEAL_INVERTED = '#00b5ad';
    public const string BLUE = '#54c8ff';
    public const string BLUE_INVERTED = '#2185d0';
    public const string VIOLET = '#a291fb';
    public const string VIOLET_INVERTED = '#6435c9';
    public const string PURPLE = '#dc73ff';
    public const string PURPLE_INVERTED = '#a333c8';
    public const string PINK = '#ff8edf';
    public const string PINK_INVERTED = '#e03997';
    public const string BROWN = '#d67c1c';
    public const string BROWN_INVERTED = '#a5673f';
    public const string GREY = '#dcddde';
    public const string GREY_INVERTED = '#767676';
    public const string BLACK = '#545454';
    public const string BLACK_INVERTED = '#1b1c1d';

    protected static function hexToAnsiColor(string $hexColor, bool $isBackground = false): string
    {
        $ansiCode = $isBackground ? 48 : 38;
        $hexColor = ltrim($hexColor, '#'); // Remove '#' if present
        [$r, $g, $b] = sscanf($hexColor, '%02x%02x%02x');

        return "\033[{$ansiCode};2;{$r};{$g};{$b}m";
    }

    public static function color(string $color, string $content): string
    {
        return self::hexToAnsiColor($color) . $content . "\033[0m"; // Reset
    }

    public static function bold(string $content, ?string $color = null): string
    {
        $bold = "\033[1m{$content}\033[0m";

        if ($color !== null) {
            $bold = self::color($color, $bold);
        }

        return $bold;
    }

    /**
     * @throws BadMethodCallException
     */
    public static function __callStatic(string $name, array $arguments): mixed
    {
        if (method_exists(self::class, $name)) {
            return self::$name(...$arguments);
        }

        if (defined(self::class . '::' . $name)) {
            return self::color(constant(self::class . '::' . $name), ...$arguments);
        }

        throw new BadMethodCallException("Method {$name} does not exist");
    }
}
