<?php

/**
 * @brief EmbedMedia, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul
 *
 * @copyright Franck Paul
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\EmbedMedia;

use Dotclear\App;
use Dotclear\Core\Backend\Notices;
use Dotclear\Core\Backend\Page;
use Dotclear\Core\Process;
use Dotclear\Helper\Html\Form\Button;
use Dotclear\Helper\Html\Form\Form;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Radio;
use Dotclear\Helper\Html\Form\Submit;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Form\Url;
use Dotclear\Helper\Html\Html;

class Manage extends Process
{
    /**
     * Initializes the page.
     */
    public static function init(): bool
    {
        // Only in popup mode
        return self::status(My::checkContext(My::MANAGE) && !empty($_REQUEST['popup']));
    }

    /**
     * Processes the request(s).
     */
    public static function process(): bool
    {
        return (bool) self::status();
    }

    /**
     * Renders the page.
     */
    public static function render(): void
    {
        if (!self::status()) {
            return;
        }

        $head = My::jsLoad('popup.js') .
            Page::jsJson('embed_media', ['embed_media' => [
                'request_error' => __('oEmbed API error: '),
            ]]);

        Page::openModule(My::name(), $head);

        echo Page::breadcrumb(
            [
                Html::escapeHTML(App::blog()->name()) => '',
                __('Embed external media')            => '',
            ]
        );
        echo Notices::getNotices();

        $i_align = [
            'none'   => [__('None'), 0],
            'left'   => [__('Left'), 0],
            'right'  => [__('Right'), 0],
            'center' => [__('Center'), 1],
        ];
        $aligns = [];
        $i      = 0;
        foreach ($i_align as $k => $v) {
            $aligns[] = (new Radio(['alignment', 'alignment' . ++$i], (bool) $v[1]))
                ->value($k)
                ->label((new Label($v[0], Label::INSIDE_TEXT_AFTER)));
        }

        // Form
        echo (new Form('media-insert-form'))
            ->action(App::backend()->getPageURL() . '&popup=1')
            ->method('post')
            ->fields([
                (new Para())->items([
                    (new Text(null, __('Please enter the URL of the page containing the media you want to include in your post.'))),
                ]),
                (new Para())->items([
                    (new Url('m_url'))
                        ->size(50)
                        ->maxlength(255)
                        ->label((new Label(__('Page URL:'), Label::INSIDE_TEXT_BEFORE))),
                ]),
                (new Text('h3', __('Media alignment'))),
                (new Para())->items([
                    ...$aligns,
                ]),
                (new Para())
                    ->separator(' ')
                    ->class('form-buttons')
                    ->items([
                        (new Submit('media-insert-ok'))
                            ->class('submit')
                            ->value(__('Insert')),
                        (new Button('media-insert-cancel'))
                            ->class('submit')
                            ->value(__('Cancel')),
                        ... My::hiddenFields(),
                    ]),
            ])
        ->render();

        Page::closeModule();
    }
}
