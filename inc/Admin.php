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

/* dotclear ns */
use dcAdmin;
use dcCore;
use dcPage;

class Admin
{
    protected static $init = false;

    public static function init(): bool
    {
        if (defined('DC_CONTEXT_ADMIN')) {
            self::$init = true;
        }

        return self::$init;
    }

    public static function process(): ?bool
    {
        if (!self::$init) {
            return false;
        }

        dcCore::app()->menu[dcAdmin::MENU_PLUGINS]->addItem(
            dcCore::app()->plugins->moduleInfo(basename(__NAMESPACE__), 'name'),
            dcCore::app()->adminurl->get('admin.plugin.' . basename(__NAMESPACE__)),
            dcPage::getPF(basename(__NAMESPACE__) . '/icon.svg'),
            preg_match('/' . preg_quote(dcCore::app()->adminurl->get('admin.plugin.' . basename(__NAMESPACE__))) . '(&.*)?$/', $_SERVER['REQUEST_URI']),
            dcCore::app()->auth->isSuperAdmin()
        );

        return true;
    }
}
