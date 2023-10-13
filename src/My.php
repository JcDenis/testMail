<?php

declare(strict_types=1);

namespace Dotclear\Plugin\testMail;

use Dotclear\App;
use Dotclear\Module\MyPlugin;

/**
 * @brief   testMail My helper.
 * @ingroup testMail
 *
 * @author      Osku (author)
 * @author      Jean-Christian Denis (author)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class My extends MyPlugin
{
    /** @var    string  Mailer name */
    public const X_MAILER = 'Dotclear';

    public static function checkCustomContext(int $context): ?bool
    {
        return App::task()->checkContext('BACKEND') && App::auth()->isSuperAdmin();
    }
}
