<?php
/**
 * @brief testMail, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugin
 *
 * @author Osku and contributors
 *
 * @copyright Jean-Crhistian Denis
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('DC_RC_PATH')) {
    return null;
}

$this->registerModule(
    'Mail test',
    'Send a simple mail from admin',
    'Osku and contributors',
    '0.2',
    [
        'requires'    => [['core', '2.24']],
        'permissions' => null,
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/testMail',
        'details'     => 'https://plugins.dotaddict.org/dc2/details/testMail',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/testMail/master/dcstore.xml',
    ]
);
