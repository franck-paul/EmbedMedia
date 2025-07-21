# EmbedMedia

Plugin for Dotclear 2

Embed external media in entry using the oEmbed API (see <https://oembed.com/>)

> oEmbed is a format for allowing an embedded representation of a URL on third party sites. The simple API allows a website to display embedded content (such as photos or videos) when a user posts a link to that resource, without having to parse the resource directly. (<https://oembed.com/>)

## Technical information

A predefined list of oEmbed API (Youtube, Vimeo, Flickr, …) is used if possible and, if not, will try to discover the oEmbed API entrypoint from the given media URL response.

Note: Intended to replace externalMedia Dotclear 2 plugin, which now requires a paid API key, and noembedMedia which have a limited list of oEmbed entrypoints. Both of them using 3rd party web services.

## Blog enabled as oEmbed provider

The blog may be enabled as an oEmbed provider (see blog parameters). In this case, oEmbed API entrypoint will be inserted in head part of entry pages (posts and pages).

The given HTML source code for the entry provided (if blog is enabled as a oEmbed provider) is encapsulated in a div.oembed including a blockquote with entry content (excerpt + content) and a p.oembed_src with entry author and entry link on entry title.

You may have to style [div].oembed and [p].oembed_src if necessary.

## Further possible developments

- Use <https://oembed.com/providers.json> as source of oEmbed entrypoint API
