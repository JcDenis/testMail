<?php

declare(strict_types=1);

namespace Dotclear\Plugin\testMail;

use Dotclear\Core\Process;

/**
 * @brief       testMail backend class.
 * @ingroup     testMail
 *
 * @author      Osku (author)
 * @author      Jean-Christian Denis (author)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Backend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::BACKEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        My::addBackendMenuItem();

        return true;
    }
}
