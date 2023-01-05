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

$prepend = implode('\\', ['Dotclear', 'Plugin', basename(__DIR__), 'Prepend']);
if (!class_exists($prepend)) {
    require __DIR__ . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'Prepend.php';

    if ($prepend::init()) {
        $prepend::process();
    }
}
