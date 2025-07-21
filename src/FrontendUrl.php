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
            // No given URL
            self::errorPage(400);
        }
        if (!str_starts_with((string) $url, (string) App::blog()->url())) {
            // Requested URL must starts with the blog URL
            self::errorPage(400);
        }
        // Check if URL is valid
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            // Invalid URL
            self::errorPage(400);
        }
        $path = '';
        if (($client = HttpClient::initClient($url, $path)) === false) {
            // Unable to init an HTTP client
            self::errorPage();
        }
        $client->setOutput(null);
        $client->get($path);
        if ($client->getStatus() !== 200) {
            // Unable to find URL
            self::errorPage(404);
        }

        $format = $_GET['format'] ?? 'json';
        if (!in_array($format, ['json', 'xml'])) {
            // Unsupported format (must be JSON or XML, in lowercase)
            self::errorPage(400);
        }

        // Check max dimensions
        $maxwidth  = $_GET['maxwidth']  ?? '800';
        $maxheight = $_GET['maxheight'] ?? '600';
        if ((int) $maxwidth === 0) {
            $maxwidth = '800';
        }
        if ((int) $maxheight === 0) {
            $maxheight = '600';
        }

        // Remove blog URL from start of requested URL
        $url    = substr((string) $url, strlen((string) App::blog()->url()));
        $status = FrontendHelper::oEmbedEntry($url, $maxwidth, $maxheight, $format, $args);
        if ($status === 200) {
            if ($format === 'xml') {
                self::serveDocument('oembed.xml', 'application/xml');
            } else {
                self::serveDocument('oembed.json', 'application/json');
            }
        } else {
            self::errorPage($status);
        }
    }

    public static function errorPage(int $code = 503): never
    {
        $codes = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            501 => 'Not Implemented',
        ];

        header('Content-Type: text/html; charset=UTF-8');
        if (in_array($code, $codes)) {
            Http::head($code, $codes[$code]);
        } else {
            Http::head(503, 'Service Unavailable');
        }
        exit;
    }
}
