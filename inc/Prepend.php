<?php
declare(strict_types=1);

namespace Dotclear\Plugin\testMail;

/* clearbricks ns */
use Clearbricks;

class Prepend
{
    private const LIBS = [
        'Admin',
        'Manage',
    ];
    protected static $init = false;

    public static function init(): bool
    {
        self::$init = defined('DC_RC_PATH');

        return self::$init;
    }

    public static function process(): ?bool
    {
        if (!self::$init) {
            return false;
        }

        foreach (self::LIBS as $lib) {
            Clearbricks::lib()->autoload([
                implode('\\', ['Dotclear','Plugin', basename(__NAMESAPCE__), $lib]) => __DIR__ . DIRECTORY_SEPARATOR . $lib . '.php'
            ]);
        }

        return true;
    }
}
