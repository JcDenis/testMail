<?php
$_menu['System']->addItem(__('Mail testor'),
	'plugin.php?p=testMail',
	'index.php?pf=testMail/icon.png',
	(preg_match('/plugin.php\?p=testMail(.*)?$/',$_SERVER['REQUEST_URI'])),
	$core->auth->isSuperAdmin());
?>