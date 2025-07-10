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
            $maxwidth  = (int) ($get['maxwidth'] ?? 0);
            $maxheight = (int) ($get['maxheight'] ?? 0);

            if ($maxwidth <= 0 || $maxheight <= 0) {
                // Prepare width and height based on video default size (blog parameter)
                $videowidth  = (int) App::blog()->settings()->system->media_video_width ;
                $videoheight = (int) App::blog()->settings()->system->media_video_height;
                if ($videowidth <= 0 && $videoheight <= 0) {
                    $videowidth  = 400;
                    $videoheight = 300;
                }

                // Compute ratio
                if ($videowidth > 0 && $videoheight > 0) {
                    $ratio = (float) $videowidth / (float) $videoheight;
                } else {
                    $ratio = 4.0 / 3.0; // Use a classical 4/3 ration
                    // Adjust default width and height
                    if ($videoheight <= 0) {
                        $videoheight = (int) ((float) $videowidth / $ratio);
                    } else {
                        $videowidth = (int) ((float) $videoheight * $ratio);
                    }
                }

                // Adjust width and height
                if ($maxwidth <= 0 && $maxheight <= 0) {
                    $maxwidth  = $videowidth;
                    $maxheight = $videoheight;
                } elseif ($maxheight <= 0) {
                    // Compute height using width and ratio
                    $maxheight = (int) ((float) $maxwidth / $ratio);
                } else {
                    // Compute width using width and ratio
                    $maxwidth = (int) ((float) $maxheight * $ratio);
                }
            }

            $embed = new Embed();
            $html  = $embed->getHtml($url, [
                'maxwidth'  => $maxwidth,
                'maxheight' => $maxheight,
            ]);

            if ($html !== false) {
                $payload = [
                    'ret'  => true,
                    'html' => $html,
                ];
            } else {
                $payload = [
                    'ret'   => false,
                    'error' => $embed->getLastErrorCode(),  // HTTP error if any
                ];
            }
        }

        return $payload;
    }
}
