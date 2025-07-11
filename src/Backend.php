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

class Backend extends Process
{
    public static function init(): bool
    {
        // dead but useful code, in order to have translations
        __('Embed Media');
        __('Embed external media from Internet');

        return self::status(My::checkContext(My::BACKEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        $settings = My::settings();
        if ($settings->active) {
            // Register REST methods
            App::rest()->addFunction('embedMedia', BackendRest::embedMedia(...));

            App::behavior()->addBehaviors([
                'adminPostEditor'               => BackendBehaviors::adminPostEditor(...),
                'ckeditorExtraPlugins'          => BackendBehaviors::ckeditorExtraPlugins(...),
                'adminBlogPreferencesFormV2'    => BackendBehaviors::adminBlogPreferencesForm(...),
                'adminBeforeBlogSettingsUpdate' => BackendBehaviors::adminBeforeBlogSettingsUpdate(...),
            ]);
        }

        return true;
    }
}
