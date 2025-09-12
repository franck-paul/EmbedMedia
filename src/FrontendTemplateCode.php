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
        echo App::frontend()->context()::global_filters(
            (string) App::frontend()->context()->oembed_title,
            $_params_,
            $_tag_
        );
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
        echo App::frontend()->context()::global_filters(
            (string) App::frontend()->context()->oembed_author,
            $_params_,
            $_tag_
        );
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
        echo App::frontend()->context()::global_filters(
            (string) App::frontend()->context()->oembed_author_url,
            $_params_,
            $_tag_
        );
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
        echo App::frontend()->context()::global_filters(
            (string) App::frontend()->context()->oembed_html,
            $_params_,
            $_tag_
        );
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
        echo App::frontend()->context()::global_filters(
            (string) App::frontend()->context()->oembed_width,
            $_params_,
            $_tag_
        );
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
        echo App::frontend()->context()::global_filters(
            (string) App::frontend()->context()->oembed_height,
            $_params_,
            $_tag_
        );
    }
}
