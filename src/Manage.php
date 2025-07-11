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
use Dotclear\Helper\Html\Form\Div;
use Dotclear\Helper\Html\Form\Form;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Note;
use Dotclear\Helper\Html\Form\Number;
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
                'request_error' => __('oEmbed HTTP error:'),
                'unknown_error' => __('Unknown error has occured, please verify URL or retry it later.'),
            ]]);

        Page::openModule(My::name(), $head);

        echo Page::breadcrumb(
            [
                Html::escapeHTML(App::blog()->name()) => '',
                __('Embed external media')            => '',
            ]
        );
        echo Notices::getNotices();

        $options = [
            'none'   => __('None'),
            'left'   => __('Left'),
            'right'  => __('Right'),
            'center' => __('Center'),
        ];
        $align_default = App::blog()->settings()->system->media_img_default_alignment ?: 'none';
        $alignments    = function () use ($options, $align_default) {
            foreach ($options as $code => $label) {
                yield (new Radio(['media-insert-alignment', 'alignement_' . $code], $code === $align_default))
                    ->value($code)
                    ->label((new Label($label, Label::IL_FT)));
            }
        };

        // Form
        echo (new Form('media-insert-form'))
            ->action(App::backend()->getPageURL() . '&popup=1')
            ->method('post')
            ->fields([
                (new Para())->items([
                    (new Text(null, __('Please enter the URL of the page containing the media you want to include in your post.'))),
                ]),
                (new Para())->items([
                    (new Url('media-insert-url'))
                        ->size(50)
                        ->maxlength(255)
                        ->label((new Label(__('Page URL:'), Label::INSIDE_TEXT_BEFORE))),
                ]),
                (new Div())
                    ->class('two-boxes')
                    ->items([
                        (new Text('h3', __('Media alignment'))),
                        (new Para())->items([
                            ...$alignments(),
                        ]),
                    ]),
                (new Div())
                    ->class('two-boxes')
                    ->items([
                        (new Text('h3', __('Maximum size (in pixels)'))),
                        (new Para())
                            ->class('field')
                            ->items([
                                (new Number('media-insert-maxwidth', 0, 999, (int) App::blog()->settings()->system->media_video_width))
                                    ->label(new Label(__('Width'), Label::OL_TF)),
                            ]),
                        (new Para())
                            ->class('field')
                            ->items([
                                (new Number('media-insert-maxheight', 0, 999, (int) App::blog()->settings()->system->media_video_height))
                                    ->label(new Label(__('Height'), Label::OL_TF)),
                            ]),
                        (new Note())
                            ->class(['form-note', 'info'])
                            ->text(__('The default width and height are based on video insertion sizes defined in blog parameters.')),
                    ]),
                (new Para())
                    ->separator(' ')
                    ->class('form-buttons')
                    ->items([
                        (new Submit('media-insert-ok'))
                            ->class(['submit'])
                            ->value(__('Insert')),
                        (new Button('media-insert-cancel'))
                            ->class('reset')
                            ->value(__('Cancel')),
                        ... My::hiddenFields(),
                    ]),
            ])
        ->render();

        Page::closeModule();
    }
}
