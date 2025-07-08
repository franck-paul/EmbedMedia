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

use DOMDocument;
use Dotclear\App;

class Helper
{
    /**
     * @var array<string, array{0: string, 1: bool}>    $providers
     *
     * Key = regular expression/URL
     * Value = [ oembed service provider URL, use regular expression (see key) ]
     */
    protected array $providers = [];

    public function __construct(
        protected ?string $host,
    ) {
        $this->host      = $host ?? App::blog()->url();
        $this->providers = [
            '#https?://((m|www)\.)?youtube\.com/watch.*#i'                        => ['https://www.youtube.com/oembed', true],
            '#https?://((m|www)\.)?youtube\.com/playlist.*#i'                     => ['https://www.youtube.com/oembed', true],
            '#https?://((m|www)\.)?youtube\.com/shorts/*#i'                       => ['https://www.youtube.com/oembed', true],
            '#https?://((m|www)\.)?youtube\.com/live/*#i'                         => ['https://www.youtube.com/oembed', true],
            '#https?://youtu\.be/.*#i'                                            => ['https://www.youtube.com/oembed', true],
            '#https?://(.+\.)?vimeo\.com/.*#i'                                    => ['https://vimeo.com/api/oembed.{format}', true],
            '#https?://(www\.)?dailymotion\.com/.*#i'                             => ['https://www.dailymotion.com/services/oembed', true],
            '#https?://dai\.ly/.*#i'                                              => ['https://www.dailymotion.com/services/oembed', true],
            '#https?://(www\.)?flickr\.com/.*#i'                                  => ['https://www.flickr.com/services/oembed/', true],
            '#https?://flic\.kr/.*#i'                                             => ['https://www.flickr.com/services/oembed/', true],
            '#https?://(.+\.)?smugmug\.com/.*#i'                                  => ['https://api.smugmug.com/services/oembed/', true],
            '#https?://(www\.)?scribd\.com/(doc|document)/.*#i'                   => ['https://www.scribd.com/services/oembed', true],
            '#https?://wordpress\.tv/.*#i'                                        => ['https://wordpress.tv/oembed/', true],
            '#https?://(.+\.)?crowdsignal\.net/.*#i'                              => ['https://api.crowdsignal.com/oembed', true],
            '#https?://(.+\.)?polldaddy\.com/.*#i'                                => ['https://api.crowdsignal.com/oembed', true],
            '#https?://poll\.fm/.*#i'                                             => ['https://api.crowdsignal.com/oembed', true],
            '#https?://(.+\.)?survey\.fm/.*#i'                                    => ['https://api.crowdsignal.com/oembed', true],
            '#https?://(www\.)?twitter\.com/\w{1,15}/status(es)?/.*#i'            => ['https://publish.twitter.com/oembed', true],
            '#https?://(www\.)?twitter\.com/\w{1,15}$#i'                          => ['https://publish.twitter.com/oembed', true],
            '#https?://(www\.)?twitter\.com/\w{1,15}/likes$#i'                    => ['https://publish.twitter.com/oembed', true],
            '#https?://(www\.)?twitter\.com/\w{1,15}/lists/.*#i'                  => ['https://publish.twitter.com/oembed', true],
            '#https?://(www\.)?twitter\.com/\w{1,15}/timelines/.*#i'              => ['https://publish.twitter.com/oembed', true],
            '#https?://(www\.)?twitter\.com/i/moments/.*#i'                       => ['https://publish.twitter.com/oembed', true],
            '#https?://(www\.)?soundcloud\.com/.*#i'                              => ['https://soundcloud.com/oembed', true],
            '#https?://(open|play)\.spotify\.com/.*#i'                            => ['https://embed.spotify.com/oembed/', true],
            '#https?://(.+\.)?imgur\.com/.*#i'                                    => ['https://api.imgur.com/oembed', true],
            '#https?://(www\.)?issuu\.com/.+/docs/.+#i'                           => ['https://issuu.com/oembed_wp', true],
            '#https?://(www\.)?mixcloud\.com/.*#i'                                => ['https://app.mixcloud.com/oembed/', true],
            '#https?://(www\.|embed\.)?ted\.com/talks/.*#i'                       => ['https://www.ted.com/services/v1/oembed.{format}', true],
            '#https?://(www\.)?(animoto|video214)\.com/play/.*#i'                 => ['https://animoto.com/oembeds/create', true],
            '#https?://(.+)\.tumblr\.com/.*#i'                                    => ['https://www.tumblr.com/oembed/1.0', true],
            '#https?://(www\.)?kickstarter\.com/projects/.*#i'                    => ['https://www.kickstarter.com/services/oembed', true],
            '#https?://kck\.st/.*#i'                                              => ['https://www.kickstarter.com/services/oembed', true],
            '#https?://cloudup\.com/.*#i'                                         => ['https://cloudup.com/oembed', true],
            '#https?://(www\.)?reverbnation\.com/.*#i'                            => ['https://www.reverbnation.com/oembed', true],
            '#https?://videopress\.com/v/.*#'                                     => ['https://public-api.wordpress.com/oembed/?for=' . $host, true],
            '#https?://(www\.)?reddit\.com/r/[^/]+/comments/.*#i'                 => ['https://www.reddit.com/oembed', true],
            '#https?://(www\.)?speakerdeck\.com/.*#i'                             => ['https://speakerdeck.com/oembed.{format}', true],
            '#https?://(www\.)?screencast\.com/.*#i'                              => ['https://api.screencast.com/external/oembed', true],
            '#https?://([a-z0-9-]+\.)?amazon\.(com|com\.mx|com\.br|ca)/.*#i'      => ['https://read.amazon.com/kp/api/oembed', true],
            '#https?://([a-z0-9-]+\.)?amazon\.(co\.uk|de|fr|it|es|in|nl|ru)/.*#i' => ['https://read.amazon.co.uk/kp/api/oembed', true],
            '#https?://([a-z0-9-]+\.)?amazon\.(co\.jp|com\.au)/.*#i'              => ['https://read.amazon.com.au/kp/api/oembed', true],
            '#https?://([a-z0-9-]+\.)?amazon\.cn/.*#i'                            => ['https://read.amazon.cn/kp/api/oembed', true],
            '#https?://(www\.)?a\.co/.*#i'                                        => ['https://read.amazon.com/kp/api/oembed', true],
            '#https?://(www\.)?amzn\.to/.*#i'                                     => ['https://read.amazon.com/kp/api/oembed', true],
            '#https?://(www\.)?amzn\.eu/.*#i'                                     => ['https://read.amazon.co.uk/kp/api/oembed', true],
            '#https?://(www\.)?amzn\.in/.*#i'                                     => ['https://read.amazon.in/kp/api/oembed', true],
            '#https?://(www\.)?amzn\.asia/.*#i'                                   => ['https://read.amazon.com.au/kp/api/oembed', true],
            '#https?://(www\.)?z\.cn/.*#i'                                        => ['https://read.amazon.cn/kp/api/oembed', true],
            '#https?://www\.someecards\.com/.+-cards/.+#i'                        => ['https://www.someecards.com/v2/oembed/', true],
            '#https?://www\.someecards\.com/usercards/viewcard/.+#i'              => ['https://www.someecards.com/v2/oembed/', true],
            '#https?://some\.ly\/.+#i'                                            => ['https://www.someecards.com/v2/oembed/', true],
            '#https?://(www\.)?tiktok\.com/.*/video/.*#i'                         => ['https://www.tiktok.com/oembed', true],
            '#https?://(www\.)?tiktok\.com/@.*#i'                                 => ['https://www.tiktok.com/oembed', true],
            '#https?://([a-z]{2}|www)\.pinterest\.com(\.(au|mx))?/.*#i'           => ['https://www.pinterest.com/oembed.json', true],
            '#https?://(www\.)?wolframcloud\.com/obj/.+#i'                        => ['https://www.wolframcloud.com/oembed', true],
            '#https?://pca\.st/.+#i'                                              => ['https://pca.st/oembed.json', true],
            '#https?://((play|www)\.)?anghami\.com/.*#i'                          => ['https://api.anghami.com/rest/v1/oembed.view', true],
            '#https?://bsky.app/profile/.*/post/.*#i'                             => ['https://embed.bsky.app/oembed', true],
            '#https?://(www\.)?canva\.com/design/.*/view.*#i'                     => ['https://canva.com/_oembed', true],
        ];
    }

    /**
     * Takes a URL and returns the corresponding oEmbed provider's URL, if there is one.
     *
     * @param string                        $url  The URL to the content.
     * @param string|array<string, mixed>   $args
     *     Optional. Additional provider arguments. Default empty.
     *
     *     @type bool $discover Optional. Determines whether to attempt to discover link tags
     *                          at the given URL for an oEmbed provider when the provider URL
     *                          is not found in the built-in providers list. Default true.
     *
     * @return string|false The oEmbed provider URL on success, false on failure.
     */
    public function getProvider(string $url, string|array $args = ''): string|bool
    {
        $provider = false;

        if (!is_array($args)) {
            $args = [$args];
        }

        if (!isset($args['discover'])) {
            $args['discover'] = true;
        }

        foreach ($this->providers as $matchmask => $data) {
            [$providerurl, $regex] = $data;

            // Turn the asterisk-type provider URLs into regex.
            if (!$regex) {
                $matchmask = '#' . str_replace('___wildcard___', '(.+)', preg_quote(str_replace('*', '___wildcard___', $matchmask), '#')) . '#i';
            }

            if (preg_match($matchmask, $url)) {
                $provider = str_replace('{format}', 'json', $providerurl); // JSON is easier to deal with than XML.

                break;
            }
        }

        if (!$provider && $args['discover']) {
            $provider = $this->discover($url);
        }

        return $provider;
    }

    /**
     * Attempts to discover link tags at the given URL for an oEmbed provider.
     *
     * @param   string      $url The URL that should be inspected for discovery `<link>` tags.
     *
     * @return  string|false The oEmbed provider URL on success, false on failure.
     */
    public function discover(string $url): string|bool
    {
        $providers = [];

        // Fetch URL content.
        $curl = curl_init();

        if ($curl === false) {
            return false;
        }

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_POST           => false,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        ]);
        if (App::config()->devMode() === true && App::config()->debugMode() === true) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }
        $response = curl_exec($curl);
        curl_close($curl);
        if ($response === false) {
            return false;
        }

        $linktypes = [
            'application/json+oembed' => 'json',
            'text/xml+oembed'         => 'xml',
        ];

        // Strip <body>.
        $html_head_end = mb_stripos((string) $response, '</head>');
        if ($html_head_end) {
            $response = mb_substr((string) $response, 0, $html_head_end);
        }

        // Do a quick check.
        $tagfound = false;
        foreach (array_keys($linktypes) as $linktype) {
            if (mb_stripos((string) $response, $linktype)) {
                $tagfound = true;

                break;
            }
        }

        if ($tagfound && preg_match_all('#<link([^<>]+)/?>#iU', (string) $response, $links)) {
            foreach ($links[1] as $link) {
                $attributes = $this->parseLinkAttributes($link);
                if (!isset($attributes['type']) || !isset($attributes['href'])) {
                    continue;
                }

                if (array_key_exists($attributes['type'], $linktypes)) {
                    $providers[$linktypes[$attributes['type']]] = htmlspecialchars_decode($attributes['href']);

                    // Stop here if it's JSON (that's all we need).
                    if ($linktypes[$attributes['type']] === 'json') {
                        break;
                    }
                }
            }
        }

        // JSON is preferred to XML.
        if (isset($providers['json']) && ($providers['json'] !== '')) {
            return $providers['json'];
        } elseif (isset($providers['xml']) && ($providers['xml'] !== '')) {
            return $providers['xml'];
        }

        return false;
    }

    /**
     * Parse link attributes and return them
     *
     * @param      string  $link   The link
     *
     * @return     array<string, string>
     */
    protected function parseLinkAttributes(string $link): array
    {
        $attributes = [];
        $doc        = new DOMDocument();
        if ($doc->loadHTML('<link ' . $link . ' />')) {
            $links = $doc->getElementsByTagName('link');
            foreach ($links as $node) {
                if ($node->hasAttributes()) {
                    foreach ($node->attributes as $a) {
                        $attributes[mb_strtolower($a->name)] = $a->value;
                    }
                }
            }
        }

        return $attributes;
    }
}
