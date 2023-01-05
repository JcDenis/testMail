<?php
/**
 * @brief testMail, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugin
 *
 * @author Osku and contributors
 *
 * @copyright Jean-Christian Denis
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
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
                implode('\\', ['Dotclear','Plugin', basename(__NAMESPACE__), $lib]) => __DIR__ . DIRECTORY_SEPARATOR . $lib . '.php',
            ]);
        }

        return true;
    }
}
