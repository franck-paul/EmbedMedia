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

class FrontendTemplateCode
{
    /**
     * PHP code for tpl:oEmbedTitle value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function oEmbedTitle(
        array $_params_,
        string $_tag_
    ): void {
        $embedmedia_title = is_string($embedmedia_title = App::frontend()->context()->oembed_title) ? $embedmedia_title : '';
        echo App::frontend()->context()::global_filters(
            $embedmedia_title,
            $_params_,
            $_tag_
        );
        unset($embedmedia_title);
    }

    /**
     * PHP code for tpl:oEmbedAuthor value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function oEmbedAuthor(
        array $_params_,
        string $_tag_
    ): void {
        $embedmedia_author = is_string($embedmedia_author = App::frontend()->context()->oembed_author) ? $embedmedia_author : '';
        echo App::frontend()->context()::global_filters(
            $embedmedia_author,
            $_params_,
            $_tag_
        );
        unset($embedmedia_author);
    }

    /**
     * PHP code for tpl:oEmbedAuthorURL value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function oEmbedAuthorURL(
        array $_params_,
        string $_tag_
    ): void {
        $embedmedia_author_url = is_string($embedmedia_author_url = App::frontend()->context()->oembed_author_url) ? $embedmedia_author_url : '';
        echo App::frontend()->context()::global_filters(
            $embedmedia_author_url,
            $_params_,
            $_tag_
        );
        unset($embedmedia_author_url);
    }

    /**
     * PHP code for tpl:oEmbedHtml value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function oEmbedHtml(
        array $_params_,
        string $_tag_
    ): void {
        $embedmedia_html = is_string($embedmedia_html = App::frontend()->context()->oembed_html) ? $embedmedia_html : '';
        echo App::frontend()->context()::global_filters(
            $embedmedia_html,
            $_params_,
            $_tag_
        );
        unset($embedmedia_html);
    }

    /**
     * PHP code for tpl:oEmbedWidth value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function oEmbedWidth(
        array $_params_,
        string $_tag_
    ): void {
        $embedmedia_width = is_numeric($embedmedia_width = App::frontend()->context()->oembed_width) ? (string) $embedmedia_width : '';
        echo App::frontend()->context()::global_filters(
            $embedmedia_width,
            $_params_,
            $_tag_
        );
        unset($embedmedia_width);
    }

    /**
     * PHP code for tpl:oEmbedHeight value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function oEmbedHeight(
        array $_params_,
        string $_tag_
    ): void {
        $embedmedia_height = is_numeric($embedmedia_height = App::frontend()->context()->oembed_height) ? (string) $embedmedia_height : '';
        echo App::frontend()->context()::global_filters(
            $embedmedia_height,
            $_params_,
            $_tag_
        );
        unset($embedmedia_height);
    }
}
