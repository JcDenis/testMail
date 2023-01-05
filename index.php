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

$manage = implode('\\', ['Dotclear', 'Plugin', basename(__DIR__), 'Manage']);
if ($manage::init()) {
    $manage::process();
    $manage::render();
}
