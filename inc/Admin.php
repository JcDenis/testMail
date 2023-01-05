<?php
declare(strict_types=1);

namespace Dotclear\Plugin\testMail;

/* dotclear ns */
use dcAdmin;
use dcCore;
use dcPage;

class Admin
{
    private static $name = '';
    protected static $init = false;

    public static function init(): bool
    {
        if (defined('DC_CONTEXT_ADMIN')) {
            self::$name = __('Mail test');
            self::$init = true;
        }

        return self::$init;
    }

    public static function process(): ?bool
    {
        if (!self::$init) {
            return false;
        }

        dcCore::app()->menu[dcAdmin::MENU_SYSTEM]->addItem(
            self::$name,
            dcCore::app()->adminurl->get('admin.plugin.' . basename(__NAMESPACE__)),
            dcPage::getPF(basename(__NAMESPACE__) . '/icon.svg'),
            preg_match('/' . preg_quote(dcCore::app()->adminurl->get('admin.plugin.' . basename(__NAMESPACE__))) . '(&.*)?$/', $_SERVER['REQUEST_URI']),
            dcCore::app()->auth->isSuperAdmin()
        );

        return true;
    }
}
