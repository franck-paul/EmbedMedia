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
     * PHP code for tpl:oEmbedHtml value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function oEmbedHtml(
        array $_params_,
        string $_tag_
    ): void {
        echo \Dotclear\Core\Frontend\Ctx::global_filters(
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
        echo \Dotclear\Core\Frontend\Ctx::global_filters(
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
        echo \Dotclear\Core\Frontend\Ctx::global_filters(
            (string) App::frontend()->context()->oembed_height,
            $_params_,
            $_tag_
        );
    }
}
