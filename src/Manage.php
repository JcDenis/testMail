<?php

declare(strict_types=1);

namespace Dotclear\Plugin\testMail;

use Dotclear\App;
use Dotclear\Core\Backend\{
    Notices,
    Page
};
use Dotclear\Core\Process;
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
use Dotclear\Helper\Network\Http;
use Dotclear\Helper\Network\Mail\Mail;
use Dotclear\Helper\Text;
use Exception;

/**
 * @brief       testMail manage class.
 * @ingroup     testMail
 *
 * @author      Osku (author)
 * @author      Jean-Christian Denis (author)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Manage extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::MANAGE));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        $active_headers = !empty($_POST['active_headers']);
        $mail_to        = $_POST['mail_to']      ?? '';
        $mail_subject   = $_POST['mail_subject'] ?? '';
        $mail_content   = $_POST['mail_content'] ?? '';

        if (!empty($mail_content) || !empty($mail_to)) {
            try {
                if (!Text::isEmail($mail_to)) {
                    throw new Exception(__('You must provide a valid email address.'));
                }

                if ($mail_content == '') {
                    throw new Exception(__('You must provide a content.'));
                }

                $mail_subject = Mail::B64Header($mail_subject);

                if ($active_headers) {
                    Mail::sendMail($mail_to, $mail_subject, $mail_content, self::getHeaders());
                } else {
                    Mail::sendMail($mail_to, $mail_subject, $mail_content);
                }
                Notices::addSuccessNotice(__('Mail successuffly sent.'));
                My::redirect();

                return true;
            } catch (Exception $e) {
                App::error()->add($e->getMessage());
            }
        }

        return true;
    }

    public static function render(): void
    {
        if (!self::status()) {
            return;
        }

        Page::openModule(My::name());

        echo
        Page::breadcrumb([
            __('System') => '',
            My::name()   => '',
        ]) .
        Notices::GetNotices() .

        (new Div('mail_testor'))
            ->__call('items', [[
                (new Form('mail_form'))
                    ->__call('method', ['post'])
                    ->__call('action', [My::manageUrl()])
                    ->__call('fields', [[
                        (new Para())
                            ->__call('items', [[
                                (new Label(__('Mailto:')))
                                    ->__call('for', ['mail_to']),
                                (new Input('mail_to'))
                                    ->__call('class', ['maximal'])
                                    ->__call('size', [30])
                                    ->__call('maxlength', [255])
                                    ->__call('value', ['']),
                            ]]),
                        (new Para())
                            ->__call('items', [[
                                (new Label(__('Subject:')))
                                    ->__call('for', ['mail_subject']),
                                (new Input('mail_subject'))
                                    ->__call('class', ['maximal'])
                                    ->__call('size', [30])
                                    ->__call('maxlength', [255])
                                    ->__call('value', ['']),
                            ]]),
                        (new Para())
                            ->__call('items', [[
                                (new Label(__('Content:')))
                                    ->__call('for', ['mail_content']),
                                (new Textarea('mail_content', ''))
                                    ->__call('class', ['maximal'])
                                    ->__call('cols', [50])
                                    ->__call('rows', [7]),
                            ]]),
                        (new Para())
                            ->__call('items', [[
                                (new Checkbox('active_headers', false))
                                    ->__call('value', [1]),
                                (new Label(__('Active mail headers'), Label::OUTSIDE_LABEL_AFTER))
                                    ->__call('for', ['active_headers'])
                                    ->__call('class', ['classic']),
                            ]]),
                        (new Para())
                            ->__call('items', [[
                                (new Submit('save'))
                                    ->__call('accesskey', ['s'])
                                    ->__call('value', [__('Send')]),
                                ... My::hiddenFields(),
                            ]]),
                    ]]),
            ]])
            ->render();

        Page::closeModule();
    }

    /**
     * @return  array<int, string>
     */
    private static function getHeaders(): array
    {
        return [
            'From: ' . Mail::B64Header(App::blog()->name()) .
            '<no-reply@' . str_replace('http://', '', Http::getHost()) . ' >',
            'Content-Type: text/HTML; charset=UTF-8;' .
            'X-Originating-IP: ' . Http::realIP(),
            'X-Mailer: ' . My::X_MAILER,
            'X-Blog-Id: ' . Mail::B64Header(App::blog()->id()),
            'X-Blog-Name: ' . Mail::B64Header(App::blog()->name()),
            'X-Blog-Url: ' . Mail::B64Header(App::blog()->url()),
        ];
    }
}
