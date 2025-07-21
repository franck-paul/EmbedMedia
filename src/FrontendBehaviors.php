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

class FrontendBehaviors
{
    public static function addTplPath(): string
    {
        App::frontend()->template()->appendPath(My::tplPath());

        return '';
    }

    public static function publicHeadContent(): string
    {
        // Only in entry context
        $urlTypes = ['post'];
        if (App::plugins()->moduleExists('pages')) {
            $urlTypes[] = 'pages';
        }
        if (!in_array(App::url()->getType(), $urlTypes)) {
            return '';
        }

        $url  = App::blog()->url() . App::url()->getURLFor('oembed');
        $apis = [
            'json' => 'application/json+oembed',
            'xml'  => 'text/xml+oembed',
        ];
        foreach ($apis as $format => $mime) {
            echo sprintf(
                '<link rel="alternate" type="%1$s" href="%2$s" />',
                $mime,
                htmlspecialchars($url . '&format=' . $format)
            ) . "\n";
        }

        return '';
    }
}
