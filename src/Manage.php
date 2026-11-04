<?php

declare(strict_types=1);

namespace Dotclear\Plugin\testMail;

use Dotclear\App;
use Dotclear\Core\Backend\Notices;
use Dotclear\Core\Backend\Page;
use Dotclear\Helper\Process\TraitProcess;
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Div;
use Dotclear\Helper\Html\Form\Form;
use Dotclear\Helper\Html\Form\Input;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Submit;
use Dotclear\Helper\Html\Form\Textarea;
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
class Manage
{
    use TraitProcess;

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
        $mail_to        = is_string($_POST['mail_to'] ?? null) ? trim($_POST['mail_to']) : '';
        $mail_subject   = is_string($_POST['mail_subject'] ?? null) ? $_POST['mail_subject'] : '';
        $mail_content   = is_string($_POST['mail_content'] ?? null) ? $_POST['mail_content'] : '';

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
        Notices::getNotices() .

        (new Div('mail_testor'))
            ->items([
                (new Form('mail_form'))
                    ->method('post')
                    ->action(My::manageUrl())
                    ->fields([
                        (new Para())
                            ->items([
                                (new Label(__('Mailto:')))
                                    ->for('mail_to'),
                                (new Input('mail_to'))
                                    ->class('maximal')
                                    ->size(30)
                                    ->maxlength(255)
                                    ->value(''),
                            ]),
                        (new Para())
                            ->items([
                                (new Label(__('Subject:')))
                                    ->for('mail_subject'),
                                (new Input('mail_subject'))
                                    ->class('maximal')
                                    ->size(30)
                                    ->maxlength(255)
                                    ->value(''),
                            ]),
                        (new Para())
                            ->items([
                                (new Label(__('Content:')))
                                    ->for('mail_content'),
                                (new Textarea('mail_content', ''))
                                    ->class('maximal')
                                    ->cols(50)
                                    ->rows(7),
                            ]),
                        (new Para())
                            ->items([
                                (new Checkbox('active_headers', false))
                                    ->value(1),
                                (new Label(__('Active mail headers'), Label::OUTSIDE_LABEL_AFTER))
                                    ->for('active_headers')
                                    ->class('classic'),
                            ]),
                        (new Para())
                            ->items([
                                (new Submit('save'))
                                    ->accesskey('s')
                                    ->value(__('Send')),
                                ... My::hiddenFields(),
                            ]),
                    ]),
            ])
            ->render();

        Page::closeModule();
    }

    /**
     * @return  string[]
     */
    private static function getHeaders(): array
    {
        return [
            'From: ' . Mail::B64Header(App::blog()->name()) .
            ' <no-reply@' . str_replace(['http://', 'https://'], '', Http::getHost()) . '>',
            'Content-Type: text/html; charset=UTF-8;',
            'X-Originating-IP: ' . Http::realIP(),
            'X-Mailer: ' . My::X_MAILER,
            'X-Blog-Id: ' . Mail::B64Header(App::blog()->id()),
            'X-Blog-Name: ' . Mail::B64Header(App::blog()->name()),
            'X-Blog-Url: ' . Mail::B64Header(App::blog()->url()),
        ];
    }
}
