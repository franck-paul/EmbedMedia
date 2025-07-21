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
use Dotclear\Plugin\TemplateHelper\Code;

class FrontendTemplate
{
    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     */
    public static function oEmbedTitle(array|ArrayObject $attr): string
    {
        return Code::getPHPTemplateValueCode(
            FrontendTemplateCode::oEmbedTitle(...),
            attr: $attr,
        );
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     */
    public static function oEmbedAuthor(array|ArrayObject $attr): string
    {
        return Code::getPHPTemplateValueCode(
            FrontendTemplateCode::oEmbedAuthor(...),
            attr: $attr,
        );
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     */
    public static function oEmbedAuthorURL(array|ArrayObject $attr): string
    {
        return Code::getPHPTemplateValueCode(
            FrontendTemplateCode::oEmbedAuthorURL(...),
            attr: $attr,
        );
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     */
    public static function oEmbedHtml(array|ArrayObject $attr): string
    {
        return Code::getPHPTemplateValueCode(
            FrontendTemplateCode::oEmbedHtml(...),
            attr: $attr,
        );
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     */
    public static function oEmbedWidth(array|ArrayObject $attr): string
    {
        return Code::getPHPTemplateValueCode(
            FrontendTemplateCode::oEmbedWidth(...),
            attr: $attr,
        );
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     */
    public static function oEmbedHeight(array|ArrayObject $attr): string
    {
        return Code::getPHPTemplateValueCode(
            FrontendTemplateCode::oEmbedHeight(...),
            attr: $attr,
        );
    }
}
