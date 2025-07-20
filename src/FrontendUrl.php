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
use Dotclear\Core\Frontend\Url;
use Dotclear\Helper\Html\Html;
use Dotclear\Helper\Network\Http;
use Dotclear\Helper\Network\HttpClient;

class FrontendUrl extends Url
{
    /**
     * @param      null|string  $args   The arguments
     */
    public static function oembed(?string $args): void
    {
        if ($_GET === []) {
            self::p404();
        }

        $url = $_GET['url'] ?? null;
        if (is_null($url)) {
            self::p404();
        }
        if (!str_starts_with($url, App::blog()->url())) {
            // Requested URL must starts with the blog URL
            self::p501();
        }
        // Check if URL is valid
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            self::p404();
        }
        $path = '';
        if (($client = HttpClient::initClient($url, $path)) === false) {
            self::p404();
        }
        $client->setOutput(null);
        $client->get($path);
        if ($client->getStatus() !== 200) {
            self::p404();
        }

        $format = $_GET['format'] ?? 'json';
        if (!in_array($format, ['json', 'xml'])) {
            // Unsupported format (must be JSON or XML)
            self::p501();
        }

        // Prepare oembed response
        $width  = $_GET['maxwidth']  ?? '800';
        $height = $_GET['maxheight'] ?? '600';
        if ((int) $width === 0) {
            $width = '800';
        }
        if ((int) $height === 0) {
            $height = '600';
        }

        $html = '<iframe width="' . $width . '" height="' . $height . '" src="' . $url . '" frameborder="0"></iframe>';

        App::frontend()->context()->oembed_html   = $format === 'xml' ? Html::escapeHTML($html) : addslashes($html);
        App::frontend()->context()->oembed_width  = $width;
        App::frontend()->context()->oembed_height = $height;

        if ($format === 'xml') {
            self::serveDocument('oembed.xml', 'application/xml');
        } else {
            self::serveDocument('oembed.json', 'application/json');
        }
    }

    /**
     * Return a 501 (not implemented) response
     */
    public static function p501(): never
    {
        header('Content-Type: text/html; charset=UTF-8');
        Http::head(501, 'Not Implemented');
        exit;
    }

    /**
     * Return a 404 (not found) response
     */
    public static function p404(): never
    {
        header('Content-Type: text/html; charset=UTF-8');
        Http::head(404, 'Not Found');
        exit;
    }
}
