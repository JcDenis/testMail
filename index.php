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
if (!defined('DC_CONTEXT_ADMIN')) {
    return null;
}

dcPage::checkSuper();

$headers = [
    'From: ' . mail::B64Header(dcCore::app()->blog->name) .
    '<no-reply@' . str_replace('http://', '', http::getHost()) . ' >',
    'Content-Type: text/HTML; charset=UTF-8;' .
    'X-Originating-IP: ' . http::realIP(),
    'X-Mailer: Dotclear',
    'X-Blog-Id: ' . mail::B64Header(dcCore::app()->blog->id),
    'X-Blog-Name: ' . mail::B64Header(dcCore::app()->blog->name),
    'X-Blog-Url: ' . mail::B64Header(dcCore::app()->blog->url),
];

$active_headers = !empty($_POST['active_headers']);
$mail_to        = $_POST['mail_to']      ?? '';
$mail_subject   = $_POST['mail_subject'] ?? '';
$mail_content   = $_POST['mail_content'] ?? '';

if (!empty($mail_content) || !empty($mail_to)) {
    try {
        if (!text::isEmail($mail_to)) {
            throw new Exception(__('You must provide a valid email address.'));
        }

        if ($mail_content == '') {
            throw new Exception(__('You must provide a content.'));
        }

        $mail_subject = mail::B64Header($mail_subject);

        if ($active_headers) {
            mail::sendMail($mail_to, $mail_subject, $mail_content, $headers);
        } else {
            mail::sendMail($mail_to, $mail_subject, $mail_content);
        }
        dcAdminNotices::addSuccessNotice(__('Mail successuffly sent.'));
        dcCore::app()->adminurl->redirect('admin.plugin.testMail');
    } catch (Exception $e) {
        dcCore::app()->error->add($e->getMessage());
    }
}

echo '<html><head><title>' . __('Mail test') . '</title></head><body>' .
dcPage::breadcrumb([__('System') => '', __('Mail test') => '']) .
dcPage::notices() . '

<div id="mail_testor">
<form method="post" action="' . dcCore::app()->admin->getPageURL() . '">

<p><label for="mail_to">' . __('Mailto:') . ' ' .
form::field('mail_to', 30, 255, $mail_to, 'maximal') .
'</label></p>

<p><label for="mail_subject">' . __('Subject:') . ' ' .
form::field('mail_subject', 30, 255, $mail_subject, 'maximal') .
'</label></p>

<p>' . __('Content:') . '</p>
<p class="area">' .
form::textarea('mail_content', 50, 7, html::escapeHTML($mail_content)) . '
</p>

<p><label class="classic" for="active_headers">' .
form::checkbox('active_headers', 1, $active_headers) . ' ' .
__('Active mail headers') .
'</label></p>

<p class="border-top">' .
'<input type="submit" value="' . __('Save') . ' (s)" accesskey="s" name="save" /> ' .

dcCore::app()->formNonce() . '</p>' .
'</form>
</div>
</body>
</html>';
