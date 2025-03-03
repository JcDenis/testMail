<?php
/**
 * @file
 * @brief       The plugin testMail definition
 * @ingroup     testMail
 *
 * @defgroup    testMail Plugin testMail.
 *
 * Send a simple mail from admin.
 *
 * @author      Osku (author)
 * @author      Jean-Christian Denis (author)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

$this->registerModule(
    'Mail test',
    'Send a simple mail from admin',
    'Osku and contributors',
    '0.7.1',
    [
        'requires'    => [['core', '2.28']],
        'permissions' => 'My',
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . $this->id . '/issues',
        'details'     => 'https://github.com/JcDenis/' . $this->id . '/',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/' . $this->id . '/master/dcstore.xml',
        'date'        => '2025-02-24T23:31:12+00:00',
    ]
);
