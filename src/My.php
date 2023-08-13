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

use dcCore;
use Dotclear\Module\MyPlugin;

/**
 * This module definitions.
 */
class My extends MyPlugin
{
    /** @var    string  Mailer name */
    public const X_MAILER = 'Dotclear';

    public static function checkXustomContext(int $context): ?bool
    {
        return defined('DC_CONTEXT_ADMIN') && dcCore::app()->auth->isSuperAdmin();
    }
}
