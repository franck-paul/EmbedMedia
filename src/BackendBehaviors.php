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
use Dotclear\Core\Backend\Page;

class BackendBehaviors
{
    public static function adminPostEditor(string $editor = ''): string
    {
        return match ($editor) {
            'dcLegacyEditor' => Page::jsJson('dc_editor_embedmedia', [
                'title'     => __('Embed external media'),
                'icon'      => urldecode(Page::getPF(My::id() . '/icon.svg')),
                'icon_dark' => urldecode(Page::getPF(My::id() . '/icon-dark.svg')),
                'open_url'  => App::backend()->url()->get('admin.plugin.' . My::id(), [
                    'popup' => 1,
                ], '&'),
                'style' => [
                    'class'  => true,
                    'left'   => 'media-left',
                    'center' => 'media-center',
                    'right'  => 'media-right',
                ],
            ]) .
            My::jsLoad('post.js'),

            'dcCKEditor' => Page::jsJson('ck_editor_embedmedia', [
                'title'        => __('Embed external media'),
                'tab_url'      => __('URL'),
                'url'          => __('Page URL:'),
                'url_empty'    => __('URL cannot be empty.'),
                'tab_align'    => __('Alignment'),
                'align'        => __('Media alignment:'),
                'align_none'   => __('None'),
                'align_left'   => __('Left'),
                'align_right'  => __('Right'),
                'align_center' => __('Center'),
                'style'        => [
                    'class'  => true,
                    'left'   => 'media-left',
                    'center' => 'media-center',
                    'right'  => 'media-right',
                ],
            ]),

            default => '',
        };
    }

    /**
     * @param      ArrayObject<int, mixed>  $extraPlugins  The extra plugins
     */
    public static function ckeditorExtraPlugins(ArrayObject $extraPlugins): string
    {
        $extraPlugins[] = [
            'name'   => 'embedmedia',
            'button' => 'EmbedMedia',
            'url'    => urldecode(App::config()->adminUrl() . Page::getPF(My::id() . '/cke-addon/')),
        ];

        return '';
    }
}
