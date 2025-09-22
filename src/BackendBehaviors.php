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

use ArrayObject;
use Dotclear\App;
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Fieldset;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Legend;
use Dotclear\Helper\Html\Form\Para;

class BackendBehaviors
{
    public static function adminPostEditor(string $editor = ''): string
    {
        return match ($editor) {
            'dcLegacyEditor' => App::backend()->page()->jsJson('dc_editor_embedmedia', [
                'title'     => __('Embed external media'),
                'icon'      => urldecode((string) App::backend()->page()->getPF(My::id() . '/icon.svg')),
                'icon_dark' => urldecode((string) App::backend()->page()->getPF(My::id() . '/icon-dark.svg')),
                'open_url'  => App::backend()->url()->get('admin.plugin.' . My::id(), [
                    'popup' => 1,
                ], '&'),
                'class' => [
                    'left'   => 'media-left',
                    'center' => 'media-center',
                    'right'  => 'media-right',
                ],
            ]) .
            My::jsLoad('post.js'),

            'dcCKEditor' => App::backend()->page()->jsJson('ck_editor_embedmedia', [
                'title'             => __('Embed external media'),
                'tab_url'           => __('URL'),
                'url'               => __('Page URL:'),
                'url_empty'         => __('URL cannot be empty.'),
                'tab_align'         => __('Alignment'),
                'align'             => __('Media alignment:'),
                'align_none'        => __('None'),
                'align_left'        => __('Left'),
                'align_right'       => __('Right'),
                'align_center'      => __('Center'),
                'align_default'     => App::blog()->settings()->system->media_img_default_alignment ?: 'none',
                'maxwidth'          => __('Max width:'),
                'maxheight'         => __('Max height:'),
                'maxwidth_default'  => App::blog()->settings()->system->media_video_width,
                'maxheight_default' => App::blog()->settings()->system->media_video_height,
                'invalid_number'    => __('Width and height must be empty or a positive integer.'),
                'caption'           => __('Caption:'),
                'class'             => [
                    'left'   => 'media-left',
                    'center' => 'media-center',
                    'right'  => 'media-right',
                ],
            ]),

            default => '',
        };
    }

    /**
     * @param      ArrayObject<int, array{name:string, url:string, button:string}>  $extraPlugins  The extra plugins
     */
    public static function ckeditorExtraPlugins(ArrayObject $extraPlugins): string
    {
        $extraPlugins[] = [
            'name'   => 'embedmedia',
            'button' => 'EmbedMedia',
            'url'    => urldecode(App::config()->adminUrl() . App::backend()->page()->getPF(My::id() . '/cke-addon/')),
        ];

        return '';
    }

    public static function adminBlogPreferencesForm(): string
    {
        $settings = My::settings();
        echo
        (new Fieldset('embed_media'))
        ->legend((new Legend(__('Embedding of external media with editors'))))
        ->fields([
            (new Para())->items([
                (new Checkbox('embedmedia_active', (bool) $settings->active))
                    ->value(1)
                    ->label((new Label(__('Enable external media embedding with editors on this blog'), Label::INSIDE_TEXT_AFTER))),
            ]),
            (new Para())->items([
                (new Checkbox('embedmedia_provider', (bool) $settings->provider))
                    ->value(1)
                    ->label((new Label(__('Enable this blog as an external media provider'), Label::INSIDE_TEXT_AFTER))),
            ]),
        ])
        ->render();

        return '';
    }

    public static function adminBeforeBlogSettingsUpdate(): string
    {
        My::settings()->put('active', empty($_POST['embedmedia_active']) ? '' : $_POST['embedmedia_active'], App::blogWorkspace()::NS_BOOL);
        My::settings()->put('provider', empty($_POST['embedmedia_provider']) ? '' : $_POST['embedmedia_provider'], App::blogWorkspace()::NS_BOOL);

        return '';
    }
}
