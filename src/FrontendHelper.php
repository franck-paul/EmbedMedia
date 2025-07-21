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
use Dotclear\Helper\Html\Html;
use Dotclear\Schema\Extension\Post;

class FrontendHelper
{
    /**
     * Retrieve entry information from given URL
     *
     * @param  string       $url          URL of entry
     * @param  string       $maxwidth     Maximum width
     * @param  string       $maxheight    Maximum height
     * @param  string       $format       Output format (JSON/XML)
     * @param  null|string  $args   The arguments
     *
     * @return int status code (same as HTTP response code, 200, 401, 404, 501, ...)
     */
    public static function oEmbedEntry(string $url, string $maxwidth, string $maxheight, string $format, ?string $args): int
    {
        // Init default value
        App::frontend()->context()->oembed_title      = App::blog()->name();                        // will be replaced by entry title
        App::frontend()->context()->oembed_author     = App::blog()->settings()->system->editor;    // will be replaced by entry author
        App::frontend()->context()->oembed_author_url = App::blog()->url();                         // will be replaced by entry author URL;

        App::frontend()->context()->oembed_html   = '';
        App::frontend()->context()->oembed_width  = $maxwidth;
        App::frontend()->context()->oembed_height = $maxheight;

        // Extract entry URL (as stored in DB)
        $url_type = null;
        $url_args = null;
        App::url()->getArgs($url, $url_type, $url_args);
        if (is_null($url_type) || is_null($url_args)) {
            // Unable to extract entry info from URL
            return 400;
        }

        // Get entry from DB
        $params = new ArrayObject(
            [
                'post_type' => '',          // Any entry type is ok
                'post_url'  => $url_args,
            ]
        );
        # --BEHAVIOR-- publicPostBeforeGetPosts -- ArrayObject, string|null
        App::behavior()->callBehavior('publicPostBeforeGetPosts', $params, $args);
        $rs = App::blog()->getPosts($params);
        if ($rs->isEmpty()) {
            // Unable to retrieve entry
            return 404;
        }

        if ($rs->post_password) {
            // Password protected entry
            return 401;
        }

        // Conversion helper
        $escape = fn (string $html) => $format === 'xml' ? Html::escapeHTML($html) : trim((string) json_encode($html, JSON_HEX_TAG | JSON_UNESCAPED_SLASHES), '"');

        // Get entry content and metadata
        $rs->extend(Post::class);

        $title      = $rs->post_title;
        $author     = $rs->getAuthorCN();
        $author_url = $rs->user_url;
        $href       = $rs->getURL();

        $link = ($author !== '' ? $author . ' - ' : '') . '<a href="' . $href . '">' . $title . '</a>';

        $html = $rs->getExcerpt(true);
        $html .= ($html !== '' ? ' ' : '') . $rs->getContent(true);

        if ($html === '') {
            // No content
            return 404;
        }

        $html = '<div class="oembed"><blockquote cite="' . $href . '">' . $html . '</blockquote><p>' . $link . '</p></div>';

        App::frontend()->context()->oembed_title      = $escape($title);
        App::frontend()->context()->oembed_author     = $escape($author);
        App::frontend()->context()->oembed_author_url = $escape($author_url);
        App::frontend()->context()->oembed_html       = $escape($html);

        return 200;
    }
}
