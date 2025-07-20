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
use Dotclear\Core\Process;

class Frontend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::FRONTEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        $settings = My::settings();
        if ($settings->active && $settings->provider) {
            App::behavior()->addBehaviors([
                'publicBeforeDocumentV2' => FrontendBehaviors::addTplPath(...),

                'publicHeadContent' => function (): string {
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
                },
            ]);

            App::frontend()->template()->addValue('oEmbedHtml', FrontendTemplate::oEmbedHtml(...));
            App::frontend()->template()->addValue('oEmbedWidth', FrontendTemplate::oEmbedWidth(...));
            App::frontend()->template()->addValue('oEmbedHeight', FrontendTemplate::oEmbedHeight(...));
        }

        return true;
    }
}
