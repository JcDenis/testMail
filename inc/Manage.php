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

namespace Dotclear\Plugin\testMail;

/* dotclear ns */
use dcAdminNotices;
use dcCore;
use dcPage;

/* clearbricks ns */
use form;
use html;
use http;
use mail;
use text;

class Manage
{
    private static $active_headers = false;
    private static $mail_to        = '';
    private static $mail_subject   = '';
    private static $mail_content   = '';
    protected static $init         = false;

    public static function init(): bool
    {
        if (defined('DC_CONTEXT_ADMIN')) {
            dcPage::checkSuper();

            self::$init = true;
        }

        return self::$init;
    }

    public static function process(): ?bool
    {
        if (!self::$init) {
            return false;
        }
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

        self::$active_headers = !empty($_POST['active_headers']);
        self::$mail_to        = $_POST['mail_to']      ?? '';
        self::$mail_subject   = $_POST['mail_subject'] ?? '';
        self::$mail_content   = $_POST['mail_content'] ?? '';

        if (!empty(self::$mail_content) || !empty(self::$mail_to)) {
            try {
                if (!text::isEmail(self::$mail_to)) {
                    throw new Exception(__('You must provide a valid email address.'));
                }

                if (self::$mail_content == '') {
                    throw new Exception(__('You must provide a content.'));
                }

                $mail_subject = mail::B64Header(self::$mail_subject);

                if ($active_headers) {
                    mail::sendMail(self::$mail_to, $mail_subject, self::$mail_content, $headers);
                } else {
                    mail::sendMail(self::$mail_to, $mail_subject, self::$mail_content);
                }
                dcAdminNotices::addSuccessNotice(__('Mail successuffly sent.'));
                dcCore::app()->adminurl->redirect('admin.plugin.' . basename(__NAMESPACE__));

                return true;
            } catch (Exception $e) {
                dcCore::app()->error->add($e->getMessage());
            }
        }

        return null;
    }

    public static function render(): void
    {
        echo
        '<html><head><title>' .
        dcCore::app()->plugins->moduleInfo(basename(__NAMESPACE__), 'name') .
        '</title></head><body>' .

        dcPage::breadcrumb([
            __('System')                                                        => '',
            dcCore::app()->plugins->moduleInfo(basename(__NAMESPACE__), 'name') => '',
        ]) .
        dcPage::notices() . '

        <div id="mail_testor">
        <form method="post" action="' . dcCore::app()->admin->getPageURL() . '">

        <p><label for="mail_to">' . __('Mailto:') . ' ' .
        form::field('mail_to', 30, 255, self::$mail_to, 'maximal') .
        '</label></p>

        <p><label for="mail_subject">' . __('Subject:') . ' ' .
        form::field('mail_subject', 30, 255, self::$mail_subject, 'maximal') .
        '</label></p>

        <p>' . __('Content:') . '</p>
        <p class="area">' .
        form::textarea('mail_content', 50, 7, html::escapeHTML($mail_content)) . '
        </p>

        <p><label class="classic" for="active_headers">' .
        form::checkbox('active_headers', 1, self::$active_headers) . ' ' .
        __('Active mail headers') .
        '</label></p>

        <p class="border-top">' .
        '<input type="submit" value="' . __('Save') . ' (s)" accesskey="s" name="save" /> ' .

        dcCore::app()->formNonce() . '</p>' .
        '</form>
        </div>

        </body></html>';
    }
}
