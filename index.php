<?php
$headers = array(
'From: '.mail::B64Header($core->blog->name).
'<no-reply@'.str_replace('http://','',http::getHost()).' >',
'Content-Type: text/HTML; charset=UTF-8;'.
'X-Originating-IP: '.http::realIP(),
'X-Mailer: Dotclear',
'X-Blog-Id: '.mail::B64Header($core->blog->id),
'X-Blog-Name: '.mail::B64Header($core->blog->name),
'X-Blog-Url: '.mail::B64Header($core->blog->url)
);

$active_headers = empty($_POST['active_headers']) ? false : true;
$mail_to = $_POST['mail_to'] ? $_POST['mail_to'] : '';
$mail_subject = !empty($_POST['mail_subject']) ? $_POST['mail_subject'] : '';
$mail_content = !empty($_POST['mail_content']) ? $_POST['mail_content'] : '';
$mail_to_send = (!empty($_POST['mail_content'])) || (!empty($_POST['mail_to'])) ? true : false;
$mail_sent = false;
if ($mail_to_send)
{
	try {
	if (!text::isEmail($mail_to)) {
		throw new Exception(__('You must provide a valid email address.'));
	}
	
	if ($mail_content == '') {
		throw new Exception(__('You must provide a content.'));
	}

	$mail_subject = mail::B64Header($mail_subject);
	
	if ($active_headers) {
	mail::sendMail($mail_to,$mail_subject,$mail_content,$headers);
	} else {
	mail::sendMail($mail_to,$mail_subject,$mail_content);
	}
	$mail_sent = true;
	//http::redirect($p_url);
	}
	catch (Exception $e)
	{
	$core->error->add($e->getMessage());
	}
}
?>

<html>
<head>
	<title><?php echo __('Mail testor'); ?></title>
</head>
<body>
<?php

echo '<h2><span class="page-title">'.__('Mail testor').'</span></h2>';

if ($mail_sent) {
	echo '<p class="message">'.__('The following mail was sent:').'</p>
	<div id="mail_convertor">
	<h3>'.$mail_subject.'</h3>'
	.mailConvert::toHTML($mail_content).
	'</div>';
}

echo 
'<div id="mail_testor">
<form method="post" action="'.$p_url.'">
<p><label class="required" for="mail_to" title="'.__('Required field').'">
<abbr title="'.__('Required field').'">*</abbr>'.
__('Mailto:').
form::field('mail_to',30,255,$mail_to).
'</label></p>'.
'<p><label for="mail_subject" title="'.__('Required field').'">'.
__('Subject:').
form::field('mail_subject',30,255,$mail_subject).
'</label></p>
<p class="area"><label class="required" for="mail_content">
<abbr title="'.__('Required field').'">*</abbr>'.
__('Content:').'</label>'.
form::textarea('mail_content',30,7,html::escapeHTML($mail_content),'maximal').
'</p>
<p>'.
form::checkbox('active_headers', 1, $active_headers).
'<label class="classic" for="active_headers"> '.
__('Active mail headers').'</label>
</p>
<p>'.form::hidden(array('p'),'testMail').
$core->formNonce().
'<input type="submit" name="sendemail" value="'.__('Send').'" />
</p>
</form>
</div>';
?>
</body>
</html>
