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

class BackendRest
{
    /**
     * Gets the HTML code for a media URL.
     *
     * @param      array<string, string>  $get    The get
     *
     * @return     array<string, mixed>   The payload.
     */
    public static function embedMedia(array $get): array
    {
        $payload = [
            'ret' => false,
        ];

        $url = $get['url'] ?? '';
        if ($url) {
            $embed     = new Embed();
            $maxwidth  = (int) ($get['maxwidth'] ?? 960);   // Should use blog parameter settings for video ?
            $maxheight = (int) ($get['maxheight'] ?? 540);  // Should use blog parameter settings for video ?

            $html = $embed->getHtml($url, [
                'maxwidth'  => $maxwidth,
                'maxheight' => $maxheight,
            ]);

            if ($html !== false) {
                $payload = [
                    'ret'  => true,
                    'html' => $html,
                ];
            }
        }

        return $payload;
    }
}
