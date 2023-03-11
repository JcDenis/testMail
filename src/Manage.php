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

use dcCore;
use dcNsProcess;
use dcPage;
use Dotclear\Helper\Html\Form\{
    Checkbox,
    Div,
    Form,
    Input,
    Label,
    Para,
    Submit,
    Textarea
};
use Exception;
use http;
use mail;
use text;

class Manage extends dcNsProcess
{
    public static function init(): bool
    {
        if (defined('DC_CONTEXT_ADMIN')) {
            dcPage::checkSuper();
            self::$init = true;
        }

        return self::$init;
    }

    public static function process(): bool
    {
        if (!self::$init) {
            return false;
        }

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
                    mail::sendMail($mail_to, $mail_subject, $mail_content, self::getHeaders());
                } else {
                    mail::sendMail($mail_to, $mail_subject, $mail_content);
                }
                dcPage::addSuccessNotice(__('Mail successuffly sent.'));
                dcCore::app()->adminurl->redirect('admin.plugin.' . My::id());

                return true;
            } catch (Exception $e) {
                dcCore::app()->error->add($e->getMessage());
            }
        }

        return true;
    }

    public static function render(): void
    {
        dcpage::openModule(My::name());

        echo
        dcPage::breadcrumb([
            __('System') => '',
            My::name()   => '',
        ]) .
        dcPage::notices() .

        (new Div('mail_testor'))->items([
            (new Form('mail_form'))->method('post')->action(dcCore::app()->admin->getPageURL())->fields([
                (new Para())->items([
                    (new Label(__('Mailto:')))->for('mail_to'),
                    (new Input('mail_to'))->class('maximal')->size(30)->maxlenght(255)->value(''),
                ]),
                (new Para())->items([
                    (new Label(__('Subject:')))->for('mail_subject'),
                    (new Input('mail_subject'))->class('maximal')->size(30)->maxlenght(255)->value(''),
                ]),
                (new Para())->items([
                    (new Label(__('Content:')))->for('mail_content'),
                    (new Textarea('mail_content', ''))->class('maximal')->cols(50)->rows(7),
                ]),
                (new Para())->items([
                    (new Checkbox('active_headers', false))->value(1),
                    (new Label(__('Active mail headers')))->for('active_headers')->class('classic'),
                ]),
                (new Para())->items([
                    (new Submit('save'))->accesskey('s')->value(__('Send')),
                    dcCore::app()->formNonce(false),
                ]),
            ]),
        ])->render();

        dcPage::closeModule();
    }

    private static function getHeaders(): array
    {
        return [
            'From: ' . mail::B64Header(dcCore::app()->blog->name) .
            '<no-reply@' . str_replace('http://', '', http::getHost()) . ' >',
            'Content-Type: text/HTML; charset=UTF-8;' .
            'X-Originating-IP: ' . http::realIP(),
            'X-Mailer: ' . My::X_MAILER,
            'X-Blog-Id: ' . mail::B64Header(dcCore::app()->blog->id),
            'X-Blog-Name: ' . mail::B64Header(dcCore::app()->blog->name),
            'X-Blog-Url: ' . mail::B64Header(dcCore::app()->blog->url),
        ];
    }
}
