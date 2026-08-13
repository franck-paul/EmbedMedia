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

if (isset($this) && is_object($this) && method_exists($this, 'registerModule') && isset($this->id) && is_string($this->id)) {
    $this->registerModule(
        'EmbedMedia',
        'Embed external media from Internet',
        'Franck Paul',
        '3.0',
        [
            'date'     => '2026-08-03T09:54:22+0200',
            'requires' => [
                ['core', '2.39'],
                ['TemplateHelper'],
            ],
            'permissions' => 'My',
            'type'        => 'plugin',
            'priority'    => 1010,  // Must be higher than dcLegacyEditor/dcCKEditor priority (ie 1000)
            'settings'    => [
                'self' => false,
                'blog' => '#params.embed_media',
            ],

            'details'    => 'https://open-time.net/?q=EmbedMedia',
            'support'    => 'https://github.com/franck-paul/EmbedMedia',
            'repository' => 'https://raw.githubusercontent.com/franck-paul/EmbedMedia/main/dcstore.xml',
            'license'    => 'gpl2',
        ]
    );
}
