<?php

/*
Plugin Name: WP Thème Switcher
Plugin URI: https://github.com/ouifm/wp-theme-switcher/
Description: Forcer l'utilisation d'un thème particulier
en envoyant un header HTTP spécifique
Version: 0.2.1
Author: Guillaume SEZNEC
Author URI: http://www.ouifm.fr
*/

/**
 *
 */
class WPThemeSwitcher
{
    /**
     * nom du header http custom
     */
    const HEADER_NAME = 'HTTP_X_WP_THEME_SWITCHER';

    /**
     * nom du paramètre en get
     */
    const GET_NAME = 'theme';

    /**
     *
     */
    static function init()
    {
        add_action('setup_theme', function () {
            add_filter('stylesheet', array(__CLASS__, 'theme_switcher'));
            add_filter('template', array(__CLASS__, 'theme_switcher'));
        });
    }

    /**
     * Force l'utilisation d'un thème particulier avec le header "X-WP-THEME-SWITCHER"
     *
     * @param string $current_theme
     * @return string
     */
    static function theme_switcher($current_theme)
    {
        $switch_to_theme = null;
        if(!empty($_GET[self::GET_NAME])) {
            $switch_to_theme = sanitize_file_name($_GET[self::GET_NAME]);
        } else if(!empty($_SERVER[self::HEADER_NAME])) {
            $switch_to_theme = sanitize_file_name($_SERVER[self::HEADER_NAME]);
        }

        if(!is_null($switch_to_theme)) {
            if(file_exists(get_theme_root() . '/' . $switch_to_theme)) {
                return $switch_to_theme;
            } else {
                wp_die('invalid theme name');
            }
        }

        return $current_theme;
    }
}

WPThemeSwitcher::init();
