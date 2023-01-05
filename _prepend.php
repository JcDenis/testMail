<?php
declare(strict_types=1);

$prepend = implode('\\', ['Dotclear', 'Plugin', basename(__DIR__), 'Prepend']);
if (!class_exists($prepend)) {
    require __DIR__ . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'Prepend.php';

    if ($prepend::init()) {
        $prepend::process();
    }
}
