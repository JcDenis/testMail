<?php
declare(strict_types=1);

$manage = implode('\\', ['Dotclear', 'Plugin', basename(__DIR__), 'Manage']);
if ($manage::init()) {
    $manage::process();
    $manage::render();
}