<?php
declare(strict_types=1);

$admin = implode('\\', ['Dotclear', 'Plugin', basename(__DIR__), 'Admin']);
if ($admin::init()) {
    $admin::process();
}